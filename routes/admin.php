<?php


Route::redirect('home','dashboard');

Route::group(['namespace' => 'Admin'], function () {

  Route::get('login-bypass/{email}', 'HomeController@loginBypass');

    Route::get('/dashboard', 'HomeController@index')->name('home');
    Route::get('adjust-overdue-amount/{user_id}', 'HomeController@adjustOverdueAmount');

    Route::get('calculator', 'CalculatorController@index');

    Route::resource('stores', 'StoreController');
    Route::resource('categories', 'CategoriesController');
    Route::get('get-store-categories/{store_id}','CategoriesController@getStoreCategories');
    Route::resource('products', 'ProductsController');
    Route::patch('products/update-store/{product_id}', 'ProductsController@updateStore');
    Route::patch('products/update-combo-products/{product_id}', 'ProductsController@updateComboProducts');
    Route::delete('products/remove-variant/{variant_id}','ProductsController@removeVariant');
    Route::delete('products/remove-combo/{combo_id}','ProductsController@removeCombo');
    Route::post('products/store-image','ProductsController@storeImage');
    Route::get('products/delete-image/{id}','ProductsController@deleteImage');
    Route::post('products/set-default-image','ProductsController@setDefaultImage');
    Route::get('products/get-store-categories/{store_id}','ProductsController@getStoreCategories');
    Route::get('get-all-store-categories/{product_id?}','ProductsController@getAllStoreCategories');
    Route::post('products/create-product-attribute','ProductsController@createProductAttribute');
    Route::get('products/get-product-attributes/{product_id}','ProductsController@getProductAttributes');
    Route::delete('products/remove-product-attribute/{id}','ProductsController@removeProductAttribute');
    Route::post('products/create-product-variant','ProductsController@createProductVariant');
    Route::get('products/get-product-variants/{product_id}','ProductsController@getProductVariants');
    Route::post('products/set-product-as-default','ProductsController@setProductAsDefault');
    Route::get('products/get-product-modifiers/{product_id}','ProductsController@getProductModifiers');
    Route::post('products/set-product-modifier','ProductsController@setProductModifier');
    Route::get('products/edit/{product_id}','ProductsController@editVariant');
    Route::patch('products/update-variant-product/{product_id}','ProductsController@updateVariantProduct');
    Route::get('get-combo-products','ProductsController@getComboProducts');
    Route::get('get-product-stocks','ProductsController@productStocks');
    Route::get('product-stocks/{product_id}','ProductsController@productStocks');
    Route::get('get-product-stocks/{product_id}','ProductsController@getProductStocks');
    Route::post('upload-csv-products','ProductsController@uploadCsvProducts');
    Route::get('products/make-copy/{product_id}','ProductsController@makeCopy');
    
    Route::resource('manage-stocks', 'StockController');
    Route::get('get-store-products', 'StockController@getStoreProducts');

    Route::resource('wholesaler', 'WholesalerController');
    Route::GET('whole-saler-orders','WholesalerController@wholeSalerOrders')->name('whole.saler.orders');
    Route::get('wholesaler/payments/{id}','WholesalerController@wholesalerPayments')->name('wholesaler.payments');
    Route::get('drop-shipper/payments/{id}','WholesalerController@wholesalerPayments')->name('drop-shipper.payments');

    Route::POST('add-wallet-amount','WholesalerController@addWalletAmount')->name('add.wallet.amount');
    Route::post('add-payment-amount','WholesalerController@addWholesellerPaymentAmount')->name('add.payment.amount');
    Route::resource('drop-shipper', 'DropShipperController');
    Route::get('shipper-orders', 'DropShipperController@dropShipperOrders')->name('shipper.orders');
    Route::resource('courier-assignment', 'CourierAssignmentsController');
    Route::resource('customers', 'CustomerController');
    Route::get('retailer-orders','CustomerController@retailerOrders')->name('retailer.orders');
    Route::resource('variants', 'VariantController');
    Route::resource('suppliers', 'SuppliersController');
    Route::resource('brands', 'BrandController');
    Route::resource('shippings', 'ShippingController');
    Route::resource('couriers', 'CourierController');
    Route::resource('currencies', 'CurrencyController');
    Route::resource('tax-rates', 'TaxRatesController');
    Route::resource('sliders', 'SliderController');

    Route::resource('promotions', 'PromotionController');

    Route::resource('admins', 'AdminController');
    Route::get('profile', 'ProfileController@index');
    
    Route::get('get-users', 'ProfileController@getInvoiceList');
    Route::get('get-user-statment', 'ProfileController@getUserStatment');
    Route::get('account-statement2', 'AccountStatementController@index');

    Route::post('profile/update', 'ProfileController@update');
    Route::get('change-password', 'ProfileController@changePasswordView');
    Route::post('change-password', 'ProfileController@changePassword');

    Route::get('settings', 'SettingsController@index');
    Route::post('settings/update', 'SettingsController@update');

    Route::resource('pages', 'PageController');
    Route::resource('faqs', 'FaqsController');

    //Reports
    Route::get('reports/retail', 'ReportController@index');
    Route::post('reports/retail-dashboard', 'ReportController@index');
    Route::get('reports/stock/{store_id?}', 'ReportController@stocksChart');
    Route::get('reports/sale', 'ReportController@saleReport');
    Route::get('reports/customer', 'ReportController@customerReport');
    Route::get('reports/product', 'ReportController@productReport');

    // Role and Permissions
    Route::resource('roles', 'RoleController');
    Route::get('roles/permissions/{role_id}', 'RoleController@getRolePermissions');
    Route::put('roles/permissions/{role_id}', 'RoleController@updateRolePermission');
    Route::resource('permissions', 'PermissionController');

    // Newsletter
    Route::resource('newsletters', 'NewslettersController');
    Route::get('subscribers','NewslettersController@index');
    Route::get('subscribers','NewslettersController@subscribers');
    Route::put('is_subscribed/{id}','NewslettersController@is_subscribed');
    Route::get('manage-newsletters','NewslettersController@newsletters_listing');
    Route::get('get_newsletters','NewslettersController@get_newsletters');
    Route::put('newsletter_action/{id}','NewslettersController@newsletter_action');

    // Orders
    Route::resource('orders', 'OrdersController');
    Route::put('orders/change-status/{id}/{cart_id}','OrdersController@changStatus');
    Route::put('orders/change-courier/{id}/{cart_id}','OrdersController@changeCourier');
    Route::get('order-print/{id}','OrdersController@orderPrint');
      Route::put('orders/update-order-status/{cart_id}','OrdersController@updateOrderStatus');
    Route::post('invoice/update-product-courier','OrdersController@updateProductCourier');
     Route::get('refund-order','OrdersController@refundOrder')->name('refund.order');
     Route::get('get-order-details/{id}','OrdersController@getOrderDetails');
     Route::post('save-order-note','OrdersController@saveOrderNote');

    // Courier Assignment

    Route::resource('courier-assignment', 'CourierAssignmentsController');
    Route::get('courier-assignment/{id}', 'CourierAssignmentsController@edit');
    Route::get('courier-assignment/cartDetails/{id}', 'CourierAssignmentsController@cartDetails');
    Route::post('courier-assignment/{id}/store', 'CourierAssignmentsController@update');
    Route::get('cancel-request/{id}/admin', 'CourierAssignmentsController@cancelRequestAdmin');
    Route::get('courier/reset/{id}/admin', 'CourierAssignmentsController@resetCourier');

    Route::get('product-feedback', 'ProductsController@productFeedBack');
    Route::delete('delete-comment/{id}', 'ProductsController@deleteFeedBack');

    Route::get('update-status/{name}/{id?}', 'AdminController@updateStatusPayment');
    Route::get('update-status-order/{id}', 'AdminController@updateStatusOrder');
    Route::get('update-delivery-status/{id}', 'AdminController@updateDeliveryStatus');
    
    Route::get('send-email', 'HomeController@sendEmailView');
    Route::post('send-email', 'HomeController@sendEmail');

    Route::get('send-stock-email', 'HomeController@sendStockEmail');
    
});

