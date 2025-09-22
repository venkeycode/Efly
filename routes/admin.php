<?php

use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SettingController;

// Route::post('login','AccountController@password')->name('loginAction');
Route::get('login', 'AccountController@login')->name('admin.login');
Route::post('login', 'AccountController@loginAction')->name('admin.loginAction');
Route::group(['middleware' => 'admin'], function () {

    Route::get('dashboard', 'AccountController@dashboard')->name('admin.dashboard');
    // Route::get('/search-products', 'ProductController@search')->name('search.products');
    // Route::get('leads', 'LeadsController@index')->name('admin.leads');
    Route::get('logout', 'AccountController@logout')->name('admin.logout');

    // Banners
    Route::resource('banner', 'BannerController')->names('admin.banners');
    // Blog
    Route::resource('blog', 'BlogController')->names('admin.blog');
    Route::post('upadte/blog/{id}', 'BlogController@update')->name('admin.blog.update');
    Route::delete('admin/blog/{id}', 'BlogController@destroy')->name('admin.blog.destroy');

    Route::resource('blogGallery', 'BlogGalleryController');

    // Route::get('import', 'BlogController@showImportForm');
    // Route::post('testImport', 'BlogController@import')->name('testImport');

    //Brands
    Route::resource('brands', 'BrandController')->names('admin.brands');
    Route::get('fetchBrands', 'BrandController@fetchBrands')->name('fetchBrands');
    Route::get('brands/delete/{id}', 'BrandController@destroy');

    //coupens

    Route::resource('coupons', 'CouponController')->names('admin.coupons');
    Route::put('coupons/update/{id}', 'CouponController@update')->name('admin.coupons.update');

    //POS
    Route::get('enquires', 'BulkOrdersEnquiriesController@index')->name('admin.enquires');
    Route::resource('taxes', 'TaxController')->names('admin.taxes');

    Route::get('pos', 'PosController@pos')->name('admin.pos');
    Route::post('posCart', 'PosController@addToCart')->name('posCart');
    Route::get('fetchCart', 'PosController@fetchCart')->name('fetchCart');

    Route::get('clearSession', 'PosController@clearSession')->name('admin.pos.clearSession');

    Route::post('posCheckout','PosOrderController@posCheckout')->name('admin.posCheckout');
    Route::get('pos/print/{id}','PosOrderController@posPrint')->name('admin.pos.print');
    Route::get('posSuccess/{id}','PosOrderController@posSuccess')->name('admin.pos.success');
    // Site Settings
    Route::resource('sitesettings', 'SiteSettingController');
    //Payments
    Route::resource('payments', 'PaymentController')->names('admin.payments');
    //Setting
    Route::resource('settings', 'SettingController');
    Route::get('setting/admin', 'SettingController@system_setting')->name('admin.settings.system');
    Route::post('setting/admin/update', 'SettingController@system_setting_update')->name('admin.settings.system.update');
    Route::get('setting/app/banners', 'SettingController@app_banners')->name('admin.settings.app.banners');
    Route::get('setting/app/banners/edit/{id}', 'SettingController@edit')->name('admin.settings.app.edit');
    Route::put('setting/app/banners/modify/{id}', 'SettingController@modify')->name('admin.settings.app.modify');
    // Route::delete('admin/setting/app/banner/{id}', 'SettingController@destroy')->name('admin.settings.app.destroy');
    // Route::delete('admin/setting/app/banner/{id}', 'admin\SettingController@destroy')->name('admin.settings.app.destroy');
    Route::delete('admin/setting/app/banner/{id}', [SettingController::class, 'destroy'])->name('admin.settings.app.destroy');

    // Contact Us
    Route::resource('contact', 'ContactController');
    //subscriptions
    Route::resource('subscriptions', 'SubscriptionController');
    //category
    Route::resource('categories', 'CategoryController')->names('admin.categories');
    Route::get('fetchCategories', 'CategoryController@fetchCategories')->name('fetchCategories');
    Route::get('fetchSubCategories', 'CategoryController@fetchSubCategories')->name('fetchSubCategories');
    Route::get('categories/delete/{id}', 'CategoryController@destroy');

    //products
    Route::resource('products', 'ProductController')->names('admin.products');
    Route::post('product/update/{id}', 'ProductController@update')->name('admin.product.update');

    Route::resource('productGallery', 'ProductGalleryController')->names('admin.productGallery');
    Route::delete('/admin/productGallery/{id}', 'ProductGalleryController@destroy')->name('admin.productGallery.destroy');
    Route::get('fetchProducts', 'ProductController@fetchProducts')->name('fetchProducts');
    Route::get('low_stocks', 'ProductController@low_stock')->name('admin.product.low_stocks');

    Route::post('featured', 'ProductController@featured');
    Route::post('featured_status', 'ProductController@featured_status');
    Route::get('delete_product/{id}', 'ProductController@delete_product');
    Route::post('editProductAttribute', 'ProductController@editProductAttribute')->name('editProductAttribute');
    Route::post('addnewProductAttribute', 'ProductController@addnewProductAttribute')->name('addnewProductAttribute');

    Route::post('importProducts', 'ProductController@importProducts')->name('importProducts');
    //units
    Route::resource('units', "UnitController")->names('admin.units');
    Route::resource('unitsAttributes', "UnitAttributeController")->names('admin.unit_attributes');;
    // Taxes
    Route::resource('taxes', 'TaxController')->names('admin.taxes');
    // Coupons
    // Route::resource('coupons', "CouponController")->name('admin.coupons');
    //offer
    Route::resource('offer', "OfferController");

    Route::resource('user', 'UserController');
    Route::resource('staff', 'StaffController')->names('admin.staff');
    Route::resource('packages', 'adminPackages');
    Route::get('packages/delete/{id}', 'adminPackages@destroy');
    Route::resource('account_management', 'AccountManagement');

    Route::get('unit_attribute', "ProductController@unit_attribute");

    Route::resource('profile_details', 'ProfileController')->names('admin.profile_details');
    Route::post('profile/update/{id}', 'ProfileController@update')->name('admin.profile_details.update');

    Route::get('/get-customer-addresses', 'PosController@getCustomerAddresses')->name('admin.getCustomerAddresses');

    // Orders
    Route::resource('adminOrders', 'OrderController')->names('admin.sales');
    Route::get('admin/order/ajax/{id}', 'OrderController@ajaxShow')->name('admin.sales.ajax');
    Route::post('admin/createParcel', 'OrderController@createParcel')->name('admin.sales.createParcel');
    Route::get('admin/ship', 'OrderController@ship')->name('admin.sales.ship');
    Route::post('admin/ship/process', 'OrderController@processShipment')->name('admin.sales.ship.process');

    Route::get('orderInvoice/{id}','OrderController@order_invoice')->name('admin.orderInvoice');
    Route::get('download_order', 'OrderController@download_order');
    Route::post('/admin/sales/updateStatus', 'OrderController@updateStatus')->name('admin.sales.updateStatus');
    // Testimonial
    Route::resource('testimonial', 'TestimonialController');
    Route::resource('services', 'ServiceController')->names('admin.services');
    Route::get('service/reviews', 'ServiceController@reviews')->name('admin.services.reviews');
    Route::post('service/reviews/store', 'ServiceController@store_review')->name('admin.services.reviews.store');
    Route::delete('service/reviews/delete/{id}', 'ServiceController@destroy_review')->name('admin.services.reviews.destroy');


    Route::resource('customers', 'CustomerController')->names('admin.customers');
    Route::get('/customer/{id}', 'CustomerController@show')->name('customer.show');
    Route::get('/customers/profile/{id}', 'CustomerController@profile')->name('customers.profile');
    Route::get('/customers/service_request/{id}', 'CustomerController@service_request')->name('customers.service_request');
    Route::get('/customers/payments/{id}', 'CustomerController@payments')->name('customers.payments');
    Route::get('/customers/orders/{id}', 'CustomerController@customer_orders')->name('customers.orders');
    Route::get('/customers/products/{id}', 'CustomerController@customer_products')->name('customers.products');
    Route::get('/customers/amc/{id}', 'CustomerController@annual_maintainence')->name('customers.amc');
    Route::get('/customers/address/{id}', 'CustomerController@address')->name('customers.address');
    Route::post('/customers/address/add/{id}', 'CustomerController@address_store')->name('customers.address_store');


    Route::resource('leads', 'LeadController')->names('admin.leads');
    Route::get('/admin/leads/filter', 'LeadController@filterLeads')->name('leads.filter');
    Route::post('leads/import', 'LeadController@import')->name('admin.leads.import');

});

Route::get('download_invoice/{id}', 'OrderController@download_invoice');
Route::get('order_invoice/{order_id}', 'OrderController@order_invoice')->name('admin.order_invoice');
