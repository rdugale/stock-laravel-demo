<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Stock;
use App\Http\Controllers\StockHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/stock_buy_sell', function () {

        $users = DB::table('users')->get();
        $stocks = DB::table('stock')->get();

        // print_r($stock);
        // dd($users);
        return view('stock_buy_sell', compact(['users', 'stocks']));
    });
});

Route::get('/', function () {
    return view('index');
})->name('home');

// Route::group(['middleware' => ['auth', 'adminrole']], function () {
//     Route::get('/add_stock', function () {
//         return view('add_stock');
//     });
// });

Route::get('/add_stock', function () {
    return view('add_stock');
})->middleware('adminrole');

// Route::get('/stock_buy_sell', function () {

//     $users = DB::table('users')->get();
//     $stocks = DB::table('stock')->get();
//     // print_r($stock);
//     // dd($users);
//     return view('stock_buy_sell',compact(['users','stocks']));
// });

Route::get('/list-stock-details/{id}', function (int $id) {

    $stock_details = DB::table('stock as sl')
        ->join('stock_histories as sh', 'sl.id', '=', 'sh.stock_id')
        ->select('sl.name', 'sh.qty as transacted_qty', DB::raw('IF(sh.qty> 0, "Buy", "Sell") as buysell'))
        ->where('sh.stock_id', $id)
        ->get();

    dd($stock_details);

});

Route::post('/stock_add_request', [Stock::class, 'store']);

Route::post('/stock_buy_sell', [StockHistory::class, 'store']);

Route::post('/stock_get_data', [StockHistory::class, 'getstockdata']);

require __DIR__.'/auth.php';
