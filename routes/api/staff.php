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

Route::post('login', 'AccountController@login');
Route::post('verifyMobile', 'AccountController@verifyMobile');
Route::post('resendOtp', 'AccountController@resendOtp');

//Product
Route::get('categories', 'ProductController@categories');
Route::get('featured_categories', 'ProductController@featured_categories');
Route::any('products', 'ProductController@products');
Route::any('product', 'ProductController@product');

Route::group(['middelware' => 'auth:sanctum'], function () {
    Route::group(['middleware' => 'verification'], function () {
        Route::get('logout', 'AccountController@logout');
        Route::get('profile', 'ProfileController@getProfile');
        Route::post('updateProfile', 'ProfileController@updateProfile');
        Route::post('updateProfilePic', 'ProfileController@updateProfilePic');
        Route::post('deliveryLocation', 'ProfileController@deliveryLocation');
        Route::get('my_locations', 'ProfileController@my_locations');
        Route::post('deleteLocation', 'ProfileController@deleteLocation');
        Route::any('firebase_token', 'ProfileController@firebase_token');

        Route::get('service', 'ServiceController@service');
        Route::get('myServices', 'ServiceController@myServices');
        Route::post('updateService', 'ServiceController@updateService');

        Route::get('leads', 'LeadController@leads');
        Route::post('lead/update', 'LeadController@update');



        //Wishlist
        Route::post('addWishList', 'WishListController@wish_list');
        Route::get('myWishList', 'WishListController@my_wishlist');
        //Cart
        Route::post('addToCart', 'CartController@add_to_cart');
        Route::post('myCart', 'CartController@myCart');
        Route::post('removeFromCart', 'CartController@removeCart');
        Route::post('update_cart', 'CartController@update_cart');
        Route::any('cartTotal', 'CartController@cartTotal');
        Route::get('clearCart', 'CartController@clearCart');
        //Orders
        Route::post('placeOrder', 'OrderController@order_placed');
        Route::get('myOrders', 'OrderController@myOrders');
        //Payments
        Route::post('payAmount', "PaymentController@payAmount");
        //Coupons
        Route::get('allCoupons', 'CouponController@allCoupons');
        Route::post('apply', 'CouponController@apply');

        Route::get('notifications', 'ProfileController@notifications');

    });
});
//setting
Route::get('banners', 'SettingController@banners');
Route::get('pages', 'SettingController@pages');
Route::get('settings', 'SettingController@settings');
Route::get('sitesettings', 'SettingController@sitesettings');
Route::get('states', 'SettingController@states');
Route::get('cities', 'SettingController@cities');