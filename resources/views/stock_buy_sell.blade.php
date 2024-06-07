@include('header')
<div class="form_div">
    <h1>Stock Buy/Sell </h1>

    <!-- @if (\Session::has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{!! \Session::get('success') !!}</li>
        </ul>
    </div>
    @endif -->

    <!-- @if(Session::has('message'))
        <div> {{ Session::get('message') }}</div>
         @endif -->
    <!-- @isset($message)
        <div> {{ $message }}</div>
        @endisset -->
    <form id="stock_buy_sell" action="{{url('stock_buy_sell')}}" method="post">
        @csrf


        <label for="user">User</label>


        <select class="select_user" name="select_user">
            @if(auth()->user()->role == 'admin' )
            @foreach ($users as $user)
            <option value="{{ $user->id}}">{{ $user->name}}</option>
            @endforeach
            @else
            <option value="{{ auth()->user()->id}}">{{ auth()->user()->name}}</option>
            @endif
        </select>


        <label for="sname">Stock Name</label>
        <select class="select_stock" name="select_stock">
            @foreach ($stocks as $stock)
            <option value="{{ $stock->id}}">{{ $stock->name}}</option>
            @endforeach
        </select>
        <label for="sqty">Stock Quantity</label>
        <input type="number" name="sqty" id="sqty" required>

        <label for="sprice">Stock Current Price</label>
        <input type="number" name="sprice" id="sprice" step="any">

        <label for="tsqty">Toal Stock Quantity</label>
        <input type="number" name="tsqty" id="tsqty" readonly step="any">

        <label for="asqty">Available Stock Quantity</label>
        <input type="number" name="asqty" id="asqty" readonly step="any">

        <label for="ownsqty">Owned Stock Quantity</label>
        <input type="number" name="ownsqty" id="ownsqty" readonly step="any">

        <button style="display:inline" type="submit" name="submit" class="buy_stock" value="buy_stock"> Buy Stock</button>

        <button style="display:inline" type="submit" name="submit" class="sell_stock" value="sell_stock"> Sell Stock</button>
    </form>

</div>

<script>
    $(document).ready(function() {

        $(document).on('change', '.select_stock', function(event) {

            let user_id = $(".select_user").val();
            let id = $(this).val();
            let csrf = $("input[name='_token']").val();
            let url = "{{url('stock_get_data')}}";
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    select_stock: id,
                    select_user: user_id,
                    _token: csrf
                },
                dataType: 'json'
            }).done(function(response) {

                //  alert(response.price);

                $('#sprice').val(parseFloat(response.stock_data.price));
                $('#tsqty').val(response.stock_data.qty);
                $('#ownsqty').val(response.user_data.owned_stock);
                $('#asqty').val(response.stock_data.available_qty);




            })

        })


        $(document).on('change', '.select_user', function(event) {

            let user_id = $(this).val();
            let id = $(".select_stock").val();
            let csrf = $("input[name='_token']").val();
            let url = "{{url('stock_get_data')}}";
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    select_stock: id,
                    select_user: user_id,
                    _token: csrf
                },
                dataType: 'json'
            }).done(function(response) {

                //  alert(response.price);

                $('#sprice').val(parseFloat(response.stock_data.price));
                $('#tsqty').val(response.stock_data.qty);
                $('#ownsqty').val(response.user_data.owned_stock);
                $('#asqty').val(response.stock_data.available_qty);




            })

        })

        let user_id = $(".select_user").val();
        let id = $(".select_stock").val();
        let csrf = $("input[name='_token']").val();
        let url = "{{url('stock_get_data')}}";
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                select_stock: id,
                select_user: user_id,
                _token: csrf
            },
            dataType: 'json'
        }).done(function(response) {

            //  alert(response.price);

            $('#sprice').val(parseFloat(response.stock_data.price));
            $('#tsqty').val(response.stock_data.qty);
            $('#ownsqty').val(response.user_data.owned_stock);
            $('#asqty').val(response.stock_data.available_qty);


        })


    });
</script>

@if(\Session::has('success'))
<script>
    alert("{!! \Session::get('success') !!}");
</script>
@endif