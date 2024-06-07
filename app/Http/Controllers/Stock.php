<?php

namespace App\Http\Controllers;

use App\Models\Stock as ModelsStock;
use Illuminate\Http\Request;

class Stock extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sname' => 'required|min:2|max:25',
            'sqty' => 'required|min:2|max:7',
            'sprice' => 'required|min:2|max:7',
        ]);
        // dd($request);
        $stock = new ModelsStock();
        $stock->name = $request->sname;
        $stock->qty = $request->sqty;
        $stock->price = $request->sprice;
        $stock->valuation = $request->sqty * $request->sprice;
        $stock->available_qty = $request->sqty;
        $stock->save();

        return redirect('/');
        // return response()->json(['status' => 'Stock Added']);
    }
}
