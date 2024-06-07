<style>
  body {
    text-align: center;
    margin: 0px;

  }

  #main_menu ul li {
    display: inline-block;
    background-color: grey;

    padding: 10px;

  }

  #main_menu ul li:hover {
    background-color: blue;
  }

  #main_menu ul li:active {
    background-color: green;
  }

  #main_menu ul li a {
    color: white;
    text-decoration: none;
  }

  #main_menu {
    background-color: grey;

  }

  table,
  th,
  td {

    border: 1px solid black;
    border-collapse: collapse;
    text-align: center;
    margin: auto;
  }

  table {

    width: 70%;
  }

  table a {
    text-decoration: none;
    text-align: center;
  }

  .nolink {

    text-decoration: none;
    background-color: green;
    color: white;
    margin: 5px;
    padding: 5px;
    text-align: center;

  }

  .flex_class {
    display: flex;
    justify-content: center;
    margin-top: 20px;
    margin-bottom: 20px;

  }

  .form_div {
    display: block;
    justify-content: center;
    background-color: lightslategray;
    width: 40%;
    margin: auto;
    padding: 10px;
    border-radius: 5px;

  }

  .form_div input {
    display: block;
    width: 80%;
    margin: auto;
    text-align: center;
    border-radius: 5px;
    padding: 5px;
    height: 20px;

  }

  .form_div select {
    display: block;
    width: 80%;
    margin: auto;
    text-align: center;
    border-radius: 5px;
    padding: 5px;
    height: 30px;

  }


  .form_div button {


    text-align: center;
    border-radius: 5px;
    margin: 20px;
    height: 45px;
    background-color: maroon;
    color: white;

  }

  #snackbar {
    visibility: hidden;
    min-width: 250px;
    margin-left: -125px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 2px;
    padding: 16px;
    position: fixed;
    z-index: 1;
    left: 50%;
    bottom: 30px;
    font-size: 17px;
  }

  #snackbar.show {
    visibility: visible;
    -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
    animation: fadein 0.5s, fadeout 0.5s 2.5s;
  }

  @-webkit-keyframes fadein {
    from {
      bottom: 0;
      opacity: 0;
    }

    to {
      bottom: 30px;
      opacity: 1;
    }
  }

  @keyframes fadein {
    from {
      bottom: 0;
      opacity: 0;
    }

    to {
      bottom: 30px;
      opacity: 1;
    }
  }

  @-webkit-keyframes fadeout {
    from {
      bottom: 30px;
      opacity: 1;
    }

    to {
      bottom: 0;
      opacity: 0;
    }
  }

  @keyframes fadeout {
    from {
      bottom: 30px;
      opacity: 1;
    }

    to {
      bottom: 0;
      opacity: 0;
    }
  }
</style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<nav id="main_menu">
  <ul>
    <li><a href="{{url('/')}}">Dashboard</a></li>
    <li><a href="{{url('stock_buy_sell')}}">Stock Buy/Sell</a></li>
    <li><a href="{{url('add_stock')}}"> Add Stock</a></li>

  </ul>

</nav>