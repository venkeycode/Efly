<?php

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'cors'], function () {
    Route::get('/customer/wallet', 'WalletController@show')->name('customer.wallet.show');
    Route::post('/customer/wallet/save', 'WalletController@save')->name('customer.wallet.save');
    Route::get('/wallet/pay', 'WalletController@showPayPage');
    Route::get('/wallet/withdraw/{id}', 'WalletController@withdraw');
    // Route::post('/wallet/withdraw/process', 'WalletController@process')->name('withdraw.approve');
    Route::get('/wallet/balance/{address}', 'WalletController@getBalance');
    Route::post('/payment/verify', 'WalletController@verifyPayment');

 });









