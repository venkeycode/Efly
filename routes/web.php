<?php

use Illuminate\Support\Facades\Route;



Route::get('/', 'PageController@home')->name('home');
Route::get('about','PageController@about')->name('about');
Route::get('destination','PageController@about')->name('destination');
Route::get('destinationdetails','PageController@about')->name('destination-details');
Route::get('booking/confirm','PageController@confirm_booking')->name('booking.confirm');

Route::get('service','PageController@service')->name('service');
Route::get('servicedetails','PageController@service')->name('service-details');
Route::get('activities','PageController@activities')->name('activities');
Route::get('activitiesdetails','PageController@activitiesdetails')->name('activities-details');
Route::get('accomodation','PageController@accomodation')->name('accomodation');
Route::get('shop','PageController@shop')->name('shop');
Route::get('shopdetails','PageController@shopdetails')->name('shop-details');
Route::get('gallery','PageController@gallery')->name('gallery');
Route::get('tour','PageController@tour')->name('tour');
Route::get('tourdetails','PageController@tourdetails')->name('tour-details');
Route::get('tourguide','PageController@tourguide')->name('tour-guide');
Route::get('tourguiderdetails','PageController@service')->name('tour-guider-details');
Route::get('faq','PageController@faq')->name('faq');
Route::get('price','PageController@price')->name('price');
Route::get('error','PageController@error')->name('error');
Route::get('cart','PageController@cart')->name('cart');
Route::get('checkout','PageController@checkout')->name('checkout');
Route::get('wishlist','PageController@wishlist')->name('wishlist');
Route::get('blogdetails','PageController@blogdetails')->name('blog-details');
Route::get('cart','PageController@cart')->name('cart');

Route::get('certifications','PageController@certifications')->name('certifications');

Route::get('register', 'PageController@register')->name('register');
Route::post('register/submit', 'PageController@registerSubmit')->name('register.submit');

Route::get('login', 'PageController@login')->name('login');
Route::post('login.submit', 'PageController@loginSubmit')->name('login.submit');
Route::get('customer/address/details',  'PageController@addressDetails')->name('customer.address.details');

Route::get('reviews', 'PageController@reviews')->name('reviews');
Route::get('CancelOrder/{id}', 'PageController@CancelOrder')->name('CancelOrder');
Route::get('ReturnOrder/{id}', 'PageController@ReturnOrder')->name('ReturnOrder');
Route::get('/review/{id}', 'PageController@ReviewCreate')->name('review.create');
Route::post('/review/{id}', 'PageController@ReviewStore')->name('review.store');
// Route::post('contact', 'PageController@contact')->name('contact');
Route::get('contact', 'PageController@contact')->name('contact');
Route::post('contact/submit', 'PageController@contactSubmit')->name('contact.submit');
Route::get('TermsandConditions', 'PageController@TermsandConditions')->name('TermsandConditions');
Route::get('PrivacyPolicy', 'PageController@PrivacyPolicy')->name('PrivacyPolicy');
Route::get('RefundPolicy', 'PageController@RefundPolicy')->name('RefundPolicy');
Route::get('blog', 'PageController@blog')->name('blog');
Route::get('blog/details/{slug}', 'PageController@blogDetail')->name('blog.detail');
Route::get('newsletter','PageContrller@newsletter')->name('newsletter');

Route::get('shop', 'PageController@products')->name('shop');
Route::get('/product/{slug}', 'PageController@product')->name('product');
Route::get('/bulkorders', 'PageController@bulkOrders')->name('bulkorders');
Route::get('/faqs', 'PageController@faqs')->name('faqs');
Route::resource('carts', 'CartController');
Route::post('store/bulkorder', 'PageController@storeBulkOrders')->name('store.bulk.orders');
Route::get('pay/{id}', 'PageController@pay')->name('pay');
Route::get('response', 'PageController@response')->name('response');
Route::get('terms', 'PageController@terms')->name('terms');
Route::get('privacy', 'PageController@privacy')->name('privacy');
Route::get('return', 'PageController@return')->name('return');
Route::get('shipping', 'PageController@shipping')->name('shipping');
Route::get('category', 'PageController@category')->name('categories');
Route::get('forgot/password', 'PageController@forgotPassword')->name('forgot.password');
Route::get('otp', 'PageController@otp')->name('otp');
Route::get('support', 'PageController@support')->name('support');
Route::get('view/ticket', 'PageController@viewTicket')->name('view.ticket');
Route::post('newletter', 'PageController@storeNewLetter')->name('footer.newletter');



// Route::get('shipping', 'PageController@layout')->name('layout');
Route::get('search-products', 'ShopController@search')->name('search.products');


Route::any('generateEnc', 'CCAvenueController@generateEnc');
Route::any('payment/{id}', 'CCAvenueController@payment')->name('ccavenue.payment');
 Route::post('ccavenue/payment/response', 'CCAvenueController@response')->name('ccavenue.payment.response');
// Route::any('ccavenue/payment/response', function (\Illuminate\Http\Request $request) {
//     \Log::info('CC Avenue Response Received');
//     \Log::info('Method: ' . $request->method());
//     \Log::info('Headers:', $request->headers->all());
//     \Log::info('Request Data:', $request->all());

//     return response()->json(['message' => 'Received successfully'], 200);
// });

Route::any('pay/now', 'CCAvenueController@getResponse')->name('ccavenue.pay');
Route::post('pay/cancel', 'CCAvenueController@hdfccancel_url')->name('ccavenue.cancel');

// Route::any('ccpay/pay', 'CCAvenueController@getResponse')->name('ccavenue.pay');
// Route::any('ccpay/response', 'CCAvenueController@getResponse')->name('ccavenue.pay');

Route::group(['middleware' => 'cors'], function () {
    Route::get('/customer/wallet', 'WalletController@show')->name('customer.wallet.show');
    Route::post('/customer/wallet/save', 'WalletController@save')->name('customer.wallet.save');
    Route::get('/wallet/balance/{address}', 'WalletController@getBalance');

 });


Route::group(['middleware' => 'user'], function () {
    Route::get('booking/confirm','PageController@confirm_booking')->name('booking.confirm');
    // Route::get('dashboard/history', 'PageController@ordersHistory')->name('dashboard.history');
    // Route::get('dashboard/rewards', 'PageController@rewardsHistory')->name('dashboard.rewards');
    // Route::get('dashboard/address', 'PageController@ordersAddress')->name('dashboard.address');
    // Route::get('dashboard/settings', 'PageController@ordersSettings')->name('dashboard.settings');
    // Route::post('dashboard/address/store', 'PageController@addressStore')->name('dashboard.address.store');
    // Route::get('logout', 'PageController@logout')->name('customer.logout');
    // Route::get('cart', 'PageController@cart')->name('cart');
    // Route::post('cart/store', 'PageController@cartStore')->name('cart.store');
    // Route::post('/carts/update/{id}', 'PageController@update')->name('carts.update');
    // Route::get('/cart/remove-coupon', 'PageController@removeCoupon')->name('remove.coupon');
    // Route::get('checkout_billingdetails', 'PageController@checkout_billing')->name('checkout.billing');
    Route::post('book', 'PageController@book')->name('book.now');
    // Route::post('store/whislist', 'PageController@storewishlist')->name('store.whislist');
    // Route::get('whislist', 'PageController@wishlist')->name('whislist');
    // Route::get('whislist/delete', 'PageController@removeWishlist')->name('whislist.delete');
    // Route::get('wishlist/cart', 'PageController@Movetocart')->name('wishlist.cart');
    // Route::get('order-details/{id}', 'PageController@orderDetails')->name('order.details');
    // Route::post('customer/update_account_details/{id}', 'PageController@update_account_details')->name('update.details');
    // Route::post('customer/update_password_details/{id}', 'PageController@update_password_details')->name('update.password');
    // Route::post('/update-reward-status', 'PageController@updateRewardStatus');
    Route::get('dashboard/thankyou/{id}', 'PageController@thankyou_page')->name('dashboard.thankyou');



    // Route::post('customer/password/update', 'PageController@updatePassword')->name('customer.password.update');
    // Route::post('customer/address/submit', 'PageController@addressSubmit')->name('customer.address.submit');
    // Route::get('customer/wishlist', 'PageController@wishlist')->name('customer.wishlist');
    // Route::post('customer/ticket', 'PageController@storeTicket')->name('customer.ticket');


});





