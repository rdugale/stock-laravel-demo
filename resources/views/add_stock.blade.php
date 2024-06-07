@include('header')

<div class="form_div">
    <h1>Add Stock</h1>
    <form id="stock_add" action="{{url('stock_add_request')}}" method="post">

        @csrf
        <label for="sname">Stock Name</label>
        <input type="text" name="sname" id="sname" value="{{ old('sname') }}" required>
        @error('sname')
        <div> {{ $message }} </div>
        @enderror

        <label for="sqty">Stock Quantity</label>
        <input type="number" name="sqty" id="sqty" value="{{ old('sqty') }}" required>
        @error('sqty')
        <div> {{ $message }} </div>
        @enderror
        <label for="sprice">Stock Initial Price</label>
        <input type="number" name="sprice" id="sprice" value="{{ old('sprice') }}" required>
        @error('sprice')
        <div> {{ $message }} </div>
        @enderror
        <button class="add_stock" type="submit"> Add Stock</button>
    </form>
</div>