@include('header')
<script type="text/javascript" src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<div class="flex_class">

    <div class="stock" style="width: 50%;">
        <h2 style="display:inline"> Stock List</h2>
        <table>
            <tr>
                <th>
                    Stock Name
                </th>
                <th>
                    Stock Price
                </th>

                <th>
                    Stock Qty
                </th>
                <th>
                    Stock Value
                </th>
            </tr>
            <tbody class="stock_list">

            </tbody>
        </table>

    </div>
    <div class="max_valuation" style="width: 50%;">
        <h2> Max Valuation</h2>
        <p style="font-weight:900;" class="company_name"> </p>
        <table>
            <tr>
                <th>
                    Stock Name
                </th>

                <th>
                    Stock Value
                </th>
                <th>
                    Stock Multiple
                </th>
            </tr>
            <tbody class="stock_max_valuation">


            </tbody>
        </table>
    </div>
</div>
<div class="flex_class">
    <div class="company_transaction" style="width: 30%;">
        <h2> Company Latest Transaction</h2>
        <table>
            <tr>
                <th>
                    Stock Name
                </th>
                <th>
                    Stock Qty
                </th>
                <th>
                    Buy/Sell
                </th>
                <th>
                    Date
                </th>
            </tr>
            <tbody class="stock_transaction">


            </tbody>
        </table>

    </div>

    <div class="chart_information" style="width: 70%;">
        <h2> Line Chart</h2>
        <div id="chartContainer" style="height: auto; width: 100%;">
        </div>



    </div>
</div>

<div class="flex_class" style="margin-top:350px !important;">

    <div class="chart_information" style="width: 100%;">
        <h2> Line Chart</h2>
        <label for="sname">Select Stock Name To See Chart</label>
        <select class="select_stock_fetch" name="select_stock_fetch">
        </select>
        <div id="chartContainerseparate" style="height: 300px; width: 100%;">
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $(document).on('change', '.select_stock_fetch', function(event) {

            let id = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{url('api/stock_get_chartdata_single/')}}/" + id,
                // data: {
                //     id: id,
                // },
                dataType: 'json'
            }).done(function(response) {

                // alert(response);

                var stockdatanew = [];
                response.forEach(function(items, index) {

                    var date_time = items.datetime.replaceAll(":", "-").replaceAll(" ", "-").split("-");

                    stockdatanew.push({
                        x: new Date(items.datetime),
                        y: Number(items.price_after)
                    })
                });

                // console.log(stockdatanew);
                var chart = new CanvasJS.Chart("chartContainerseparate", {

                    title: {
                        text: "Stock Price "
                    },
                    data: [{
                        type: "line",

                        dataPoints: stockdatanew

                    }]
                });

                chart.render();

            })

        })

        let stock_list = '';
        let select_stock_fetch = '<option  value="0" >Select Stock</option>';

        $.ajax({
            type: "GET",
            url: "{{url('api/stock_get_list')}}",
            dataType: 'json'

        }).done(function(response) {
            response.forEach(function(items, index) {

                stock_list += `<tr> <td>${items.name}</td><td>${items.price}</td> <td>${items.qty}</td><td>${items.price * items.qty}</td></tr>`;

                select_stock_fetch += `<option  value="${items.id}" >${items.name}</option>`;


            });

            $('.stock_list').html(stock_list);

            $('.select_stock_fetch').html(select_stock_fetch);


        }).fail(function() {
            alert('Receiving Stock Data Failed');

        });


        let stock_transaction = '';

        $.ajax({
            type: "GET",
            url: "{{url('api/stock_get_transaction')}}",
            dataType: 'json'

        }).done(function(response) {
            response.forEach(function(items, index) {
                let color = '';
                let action = '';
                if (items.qty > 0) {
                    color = 'green';
                    action = 'Buy';
                } else {
                    color = 'red';
                    action = 'Sell';
                }

                let date = new Date(items.datetime);

                stock_transaction += `<tr style="background-color:${color};"> <td>${items.name}</td> <td>${items.qty}</td> <td>${action}</td> <td>${date.toLocaleString()}</td></tr>`;


            });

            $('.stock_transaction').append(stock_transaction);

        }).fail(function() {
            alert('Receiving User Data Failed');

        });

        let stock_max_valuation = '';

        $.ajax({
            type: "GET",
            url: "{{url('api/stock_get_valuation')}}",
            dataType: 'json'

        }).done(function(response) {
            let maxvaluation = response[0].valuation;
            $('.company_name').html("Stock Name :" + response[0].name + " Valuation :" + maxvaluation);

            response.forEach(function(items, index) {



                if (index != 0) {
                    let multiplier = maxvaluation / items.valuation

                    stock_max_valuation += `<tr> <td>${items.name}</td> <td>${items.valuation}</td> <td>${multiplier.toFixed(2)}</td></tr>`;
                }


            });

            $('.stock_max_valuation').append(stock_max_valuation);

        }).fail(function() {
            alert('Receiving User Data Failed');

        });

        $.ajax({
            type: 'GET',
            url: "{{url('api/stock_get_chartdata')}}",
            dataType: 'json'
        }).done(function(response) {

            var stockdata = [];

            var unique = [...new Set(response.map(item => item.name))];
            // console.log("uniques:", unique);

            unique.forEach(function(items, index) {
                var result_array = response.filter(function(el) {
                    return (el.name == items);
                });
                //  console.log("result_array", result_array);
                var stock_data = [];
                result_array.forEach(function(items, index) {
                    stock_data.push({
                        x: new Date(items.datetime),
                        y: Number(items.price_after)
                    })

                });

                stockdata.push({
                    type: "line",
                    axisYType: "secondary",
                    name: items,
                    showInLegend: true,
                    markerSize: 0,
                    dataPoints: stock_data

                }, )


            });

            // console.log(stockdata);
            var chartall = new CanvasJS.Chart("chartContainer", {
                title: {
                    text: "Stock Details"
                },
                axisX: {
                    valueFormatString: "MMM YYYY"
                },
                axisY2: {
                    title: "Price",
                    prefix: "Rs",
                    suffix: ""
                },
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor: "pointer",
                    verticalAlign: "top",
                    horizontalAlign: "center",
                    dockInsidePlotArea: true,

                },
                data: stockdata
            });

            chartall.render();

        })
    });
</script>

@if(\Session::has('error'))
<script>
    alert("{!! \Session::get('error') !!}");
</script>
@endif