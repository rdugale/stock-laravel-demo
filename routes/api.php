<?php

use App\Http\Controllers\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/stock_get_valuation', [StockHistory::class, 'getstockvaluation']);

Route::get('/stock_get_list', [StockHistory::class, 'getstocklist']);

Route::get('/stock_get_transaction', [StockHistory::class, 'getstocktransaction']);

Route::get('/stock_get_chartdata', [StockHistory::class, 'getstockchartdata']);

Route::get('/stock_get_chartdata_single/{id}', [StockHistory::class, 'getstockchartdatasingle']);
