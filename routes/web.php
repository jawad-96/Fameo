<?php

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

Route::get('/clear-cache', function() {
     \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    return "All cache cleared";
});

Route::get('/home', function () {
    return redirect(url('/'));
});
Route::get('test', 'HomeController@test');
Route::get('test-barcode/{barcode}', 'HomeController@testBarcode');

Auth::routes();
Route::get('{user_type}/register', 'Auth\RegisterController@userTypeRegister');

Route::group(['middleware' => ['auth']], function () {

    /*cart routes*/
    Route::get('/make-payment', 'CartController@makePayment');
    Route::get('/checkout-payment', 'CartController@cart');
    Route::post('/save-user-info', 'CartController@saveUserInfo');
    Route::post('/paypal-transaction-complete', 'CartController@CompletePayment');
    Route::get('/proceed-admin-request', 'CartController@proceedAdminRequest');
    Route::get('/cancel-request/{id}/customer', 'CartController@cancelRequestCustomer');

    /*wishlist routs*/
    Route::post('add-to-wishlist', 'UserWishListController@addToWishlist')->name('add-to-wishlist');
    Route::post('paywith-wallet', 'CartController@paymentFromWallet')->name('paywith-wallet');
    Route::post('get-wishlist', 'UserWishListController@getWishlist')->name('getWishlist');
    Route::get('my-wishlist', 'UserWishListController@myWishlist')->name('myWishlist');

    /*orders*/
    Route::get('/my-orders', 'CartController@myOrders');
    Route::post('get-my-orders', 'CartController@getMyOrders');
    Route::get('/transaction-details/{id}', 'CartController@transactionDetails');
    Route::get('profile', 'ProfileController@index');
    Route::post('update-profile', 'ProfileController@updateProfile');
    Route::get('/track-order', 'CartController@trackOrder');
    Route::post('/order-status', 'CartController@OrderStatus');
       Route::get('return-order', 'CartController@returnOrder')->name('return.order');

});
Route::post('get-transaction-details', 'CartController@getTransactionDetails');

/*cart routes*/
Route::post('/cart-add', 'CartController@add')->name('cart.add');
Route::get('/cart-checkout', 'CartController@cart')->name('cart.checkout');
Route::post('/cart-details', 'CartController@cartDetails');
Route::get('/cart-details1/{id}', 'CartController@cartDetails1');
Route::post('/cart-clear', 'CartController@clear')->name('cart.clear');
Route::delete('/cart-remove', 'CartController@remove');
Route::patch('/cart-update', 'CartController@update');
Route::post('/update-shipment', 'CartController@updateShipment');
ROUTE::GET('/get-invoice-detail/{id}','CartController@getInvoiceDetail');

// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');
Route::resource('cart', 'CartController');

Route::resource('categories', 'CategoriesController');
Route::post('categories/get-categories', 'CategoriesController@getCategories');

Route::get('/top-categories', [\App\Http\Controllers\HomeController::class, 'topCategories'])->name('top.categories');

Route::resource('products', 'ProductsController');
//Route::post('products/get-products', 'ProductsController@getProducts');
Route::post('get-products', 'ProductsController@getProducts');
Route::post('get-brands', 'ProductsController@getBrands');
Route::post('get-product-variants', 'ProductsController@getProductVariants');
Route::post('add-product-review', 'ProductsController@addProductReview');
Route::post('get-product-review', 'ProductsController@getProductReview');
Route::post('get-variant-product', 'ProductsController@getVariantProduct');

Route::group(['prefix' => 'admin'], function () {
  Route::get('/login', [\App\Http\Controllers\AdminAuth\LoginController::class, 'showLoginForm']);
  Route::post('/login', [\App\Http\Controllers\AdminAuth\LoginController::class, 'login']);
  Route::post('/logout', [\App\Http\Controllers\AdminAuth\LoginController::class, 'logout'])->name('logout');

  Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'AdminAuth\RegisterController@register');

  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});

Route::post('contact-us', 'PagesController@contact_us');

// page routes
Route::get('faqs', 'PagesController@faqs');
Route::get('/page/{slug}', 'PagesController@page');

// newsletter subscription
Route::post('newsletter-subscribed', 'Admin\NewslettersController@user_subscribed');
Route::get('unsubscribe-newsletter', 'Admin\NewslettersController@unsubscribe_newsletter');
Route::get('brands', 'ProductsController@brands');

Route::get('get-authcode', 'ProductsController@getAuthCode');

Route::post('get-home-products', 'HomeController@get_home_products');



Route::get('forget-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');


