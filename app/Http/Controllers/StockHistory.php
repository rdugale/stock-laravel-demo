<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockHistory extends Controller
{
    public function store(Request $request)
    {

        $validated = $request->validate([
            'select_stock' => 'required',
            'select_user' => 'required',
            'sqty' => 'required',
        ]);

        //  dd($request);

        // following is alternate way to access authenticated user
        // Auth::user()->id
        if (auth()->user()->id != $request->select_user) {
            return redirect()->back()->with('success', 'You Are Not Authorised To Buy/Sell Stock of Other User');
        }
        $new_stock_price = $request->sprice + ($request->sqty / $request->tsqty) * $request->sprice;

        if ($request->submit == 'buy_stock') {

            $stock_count = DB::table('stock as sl')
                ->join('stock_histories as sh', 'sl.id', '=', 'sh.stock_id')
                ->select('sl.name', 'sl.qty as total', DB::raw('sl.qty - SUM(sh.qty) as remaining'), DB::raw('COUNT(*) as count'))
                ->where('sh.stock_id', $request->select_stock)
                ->get()->first();

            if ($stock_count->count > 0 && $stock_count->remaining >= $request->sqty) {

                $affected = DB::table('stock')
                    ->where('id', $request->select_stock)
                    ->update(['price' => $new_stock_price, 'valuation' => $request->tsqty * $new_stock_price, 'available_qty' => $stock_count->remaining - $request->sqty]);

                DB::table('stock_histories')->insert([
                    'stock_id' => $request->select_stock,
                    'user_id' => $request->select_user,
                    'qty' => $request->sqty,
                    'price_before' => $request->sprice,
                    'price_after' => $new_stock_price,
                ]);

                // View::make('index')->with("message","Stock Price Updated to :" . number_format($new_stock_price, 2));

                //  Redirect::to('/')->with("message","Stock Price Updated to :" . number_format($new_stock_price, 2));

                return redirect()->back()->with('success', 'Stock Price Updated to :'.number_format($new_stock_price, 2));

            } elseif ($stock_count->count == 0) {

                $stock_detail = DB::table('stock')
                    ->select('qty')
                    ->where('id', $request->select_stock)
                    ->get()->first();

                if ($stock_detail->qty >= $request->sqty) {

                    $affected = DB::table('stock')
                        ->where('id', $request->select_stock)
                        ->update(['price' => $new_stock_price, 'valuation' => $request->tsqty * $new_stock_price, 'available_qty' => $stock_detail->qty - $request->sqty]);

                    DB::table('stock_histories')->insert([
                        'stock_id' => $request->select_stock,
                        'user_id' => $request->select_user,
                        'qty' => $request->sqty,
                        'price_before' => $request->sprice,
                        'price_after' => $new_stock_price,
                    ]);

                    return redirect()->back()->with('success', 'Stock Price Updated to :'.number_format($new_stock_price, 2));

                    // View::make('index')->with("message","Stock Price Updated to :" . number_format($new_stock_price, 2));
                } else {
                    //View::make('index')->with("message","Not Enought Quantity To Available For Buy");
                    // echo "Not Enought Quantity To Available For Buy";

                    return redirect()->back()->with('success', 'Not Enought Quantity To Available For Buy');
                }
            } elseif ($stock_count->count > 0 && $request->qty > $stock_count->remaining) {

                return redirect()->back()->with('success', 'Not Enought Quantity To Available For Buy');
                //View::make('index')->with("message","Not Enought Quantity To Available For Buy");

                //   echo "Not Enought Quantity To Available For Buy";
            }

            //  echo "buy clicked $stock_count->count";

            //dd($stock_count);
        } elseif ($request->submit == 'sell_stock') {

            $new_stock_price = $request->sprice - ($request->sqty / $request->tsqty) * $request->sprice;

            $stock_count = DB::table('stock as sl')
                ->join('stock_histories as sh', 'sl.id', '=', 'sh.stock_id')
                ->select('sl.name', 'sl.qty as total', DB::raw('sl.qty - SUM(sh.qty) as remaining'), DB::raw('COUNT(*) as count'))
                ->where('sh.stock_id', $request->select_stock)
                ->get()->first();

            $stock_count_history = DB::table('stock as sl')
                ->join('stock_histories as sh', 'sl.id', '=', 'sh.stock_id')
                ->select('sl.name', 'sl.qty as total', DB::raw('SUM(sh.qty) as remaining'), DB::raw('COUNT(*) as count'))
                ->where('sh.stock_id', $request->select_stock)
                ->where('sh.user_id', $request->select_user)
                ->get()->first();

            // print_r($request);

            //  print_r($stock_count_history);

            if ($stock_count_history->count > 0 && $stock_count_history->remaining >= $request->sqty) {

                //dd($stock_count_history,$request);

                $affected = DB::table('stock')
                    ->where('id', $request->select_stock)
                    ->update(['price' => $new_stock_price, 'valuation' => $request->tsqty * $new_stock_price, 'available_qty' => $stock_count->remaining + $request->sqty]);

                DB::table('stock_histories')->insert([
                    'stock_id' => $request->select_stock,
                    'user_id' => $request->select_user,
                    'qty' => -$request->sqty,
                    'price_before' => $request->sprice,
                    'price_after' => $new_stock_price,
                ]);

                return redirect()->back()->with('success', 'Stock Price Updated to :'.number_format($new_stock_price, 2));

                //  View::make('index')->with("message","Stock Price Updated to :" . number_format($new_stock_price, 2));
                // echo "Stock Price Updated to :" . number_format($new_stock_price, 2);
                // } else {
                //     echo "Error While Updating Stock Price";
                // }
            } elseif ($stock_count_history->count == 0) {
                return redirect()->back()->with('success', "You Don't Own The Stock For Selling");
                //echo "You Don't Own The Stock For Selling";

                //   View::make('index')->with("message","You Don't Own The Stock For Selling");
            } elseif ($stock_count_history->count > 0 && $request->sqty > $stock_count_history->remaining) {
                return redirect()->back()->with('success', 'You Are Selling More Than You Own');

                //   View::make('index')->with("message","You Are Selling More Than You Own");
                //  echo "You Are Selling More Than You Own";
            }

            //  echo "sell clicked";

            //  dd($stock_count);
        }

        // dd($request);

    }

    public function getstockdata(Request $request)
    {

        $output = [];
        $stock_count = DB::table('stock')
            ->select('*')
            ->where('id', $request->select_stock)
            ->get()->first();

        $user_count = DB::table('stock as sl')
            ->join('stock_histories as sh', 'sl.id', '=', 'sh.stock_id')
            ->select(DB::raw('IFNULL(SUM(sh.qty), 0) as owned_stock'), DB::raw('COUNT(*) as count'))
            ->where('sh.stock_id', $request->select_stock)
            ->where('sh.user_id', $request->select_user)
            ->get()->first();

        $output['stock_data'] = $stock_count;
        $output['user_data'] = $user_count;

        return json_encode($output);
    }

    public function getstockvaluation(Request $request)
    {

        $output = [];
        $stock_count = DB::table('stock')
            ->select('*')
            ->orderBy('valuation', 'desc')
            ->get();

        $output = $stock_count;

        return json_encode($output);
    }

    public function getstocklist(Request $request)
    {
        $output = [];
        $stock_list = DB::table('stock')
            ->select('*')
            ->get();
        $output = $stock_list;

        return json_encode($output);
    }

    public function getstocktransaction(Request $request)
    {
        $output = [];
        $transaction = DB::select(('SELECT sl.name,sh.* FROM stock_histories sh INNER JOIN stock sl on sl.id = sh.id WHERE sh.datetime IN (SELECT MAX(datetime) FROM stock_histories GROUP BY id HAVING sh.id = sh.id) ORDER BY sh.datetime DESC'));
        $output = $transaction;

        return json_encode($output);
    }

    public function getstockchartdata(Request $request)
    {

        $output = [];

        $chart_data = DB::table('stock as sl')
            ->join('stock_histories as sh', 'sl.id', '=', 'sh.stock_id')
            ->select(DB::raw('sl.name'), DB::raw('sh.*'))
            ->orderBy('datetime', 'asc')
            ->get();

        $output = $chart_data;

        //  SELECT sl.name, sh.* FROM stock sl INNER JOIN stock_histories sh on sl.id = sh.id ORDER BY datetime ASC

        return json_encode($output);
    }

    public function getstockchartdatasingle(Request $request, $id)
    {

        $output = [];

        $chart_data = DB::table('stock as sl')
            ->join('stock_histories as sh', 'sl.id', '=', 'sh.stock_id')
            ->select(DB::raw('sl.name'), DB::raw('sh.*'))
            ->where('sh.stock_id', $id)
            ->orderBy('sh.datetime', 'asc')
            ->get();

        $output = $chart_data;

        //  SELECT sl.name, sh.* FROM stock sl INNER JOIN stock_histories sh on sl.id = sh.id ORDER BY datetime ASC

        return json_encode($output);
    }
}
