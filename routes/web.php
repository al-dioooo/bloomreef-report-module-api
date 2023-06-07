<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\PettyCashController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::apiResource('petty-cash', PettyCashController::class);
Route::get('/petty-cash', [PettyCashController::class, 'index']);
Route::post('/petty-cash/store', [PettyCashController::class, 'store']);
Route::patch('/petty-cash/update', [PettyCashController::class, 'update']);
Route::delete('/petty-cash/destroy', [PettyCashController::class, 'destroy']);

Route::get('/bill', [BillController::class, 'index']);
Route::post('/bill/store', [BillController::class, 'store']);
Route::patch('/bill/update', [BillController::class, 'update']);
Route::delete('/bill/destroy', [BillController::class, 'destroy']);

Route::get('/cash-flow', [CashFlowController::class, 'index']);

Route::get('/balance', [BalanceController::class, 'index']);
Route::post('/balance/store', [BalanceController::class, 'store']);
