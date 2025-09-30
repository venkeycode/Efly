<?php

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

Route::post('payment/verify', 'WalletController@verifyTokenPayment');
Route::post('/withdrawals/{id}/send', 'WithdrawController@send');
Route::post('withdraw/usdt',  'WithdrawController@sendUsdt');
Route::post('withdraw/{id}/approve', 'WalletController@approveSingle')
    ->name('admin.withdraw.approve');
