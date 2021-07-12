<?php

/*=================================================================================================================
 * LOGIN & RESET PASSWORD
 =================================================================================================================*/
//LOGIN
Route::group(array('prefix' => 'admin/login', 'middleware' => 'guest.admin', 'namespace' => 'Admin\Auth'), function() {

    Route::get('/', 'LoginController@index')
            ->name('admin.login');

    Route::post('/', 'LoginController@login')
            ->name('admin.login.submit');
});

//RESET PASSWORD
Route::group(array('prefix' => 'admin/password', 'middleware' => 'guest.admin', 'namespace' => 'Admin\Auth'), function() {

    Route::get('forgot', 'ForgotPasswordController@index')
            ->name('admin.password.forgot');

    Route::post('email', 'ForgotPasswordController@sendResetLinkEmail')
            ->name('admin.password.forgot.email');

    Route::get('reset/{token}', 'ResetPasswordController@showResetForm')
        ->name('admin.password.reset');

    Route::post('reset', 'ResetPasswordController@reset')
        ->name('admin.password.reset.submit');

});

//LOGOUT
Route::get('admin/logout', 'Admin\Auth\LoginController@logout')
        ->name('admin.logout')
        ->middleware('auth.admin');


/*=================================================================================================================
 * GLOBAL APP ROUTES
 =================================================================================================================*/
Route::group(array('prefix' => 'admin', 'middleware' => 'auth.admin', 'namespace' => 'Admin'), function() {


    /*=================================================================================================================
     * DASHBOARD & GLOBAL ROUTES
     =================================================================================================================*/
    Route::get('/', 'HomeController@index')
            ->name('admin.dashboard');

    /*=================================================================================================================
     * MANAGE MY ACCOUNT
     =================================================================================================================*/
    Route::post('account/password', 'AccountController@password')
        ->name('admin.account.password');

    Route::post('account/payment/confirm', 'AccountController@paymentConfirm')
        ->name('admin.account.payment.confirm');


    /*=================================================================================================================
     * USERS
     =================================================================================================================*/
    Route::post('users/datatable', 'UsersController@datatable')
        ->name('admin.users.datatable');

    Route::post('users/selected/destroy', 'UsersController@massDestroy')
        ->name('admin.users.selected.destroy');

    Route::post('users/{id}/remote-login', 'UsersController@remoteLogin')
        ->name('admin.users.remote-login');

    Route::get('users/{id}/remote-logout', 'HomeController@remoteLogout')
        ->name('admin.users.remote-logout');

    Route::resource('users', 'UsersController', [
        'as' => 'admin',
        'except' => ['show']]);

    //ROLES & PERMISSIONS
    Route::resource('roles', 'RolesController', [
                    'as' => 'admin', 
                    'except' => ['create', 'edit']]);
    
    /*=================================================================================================================
     * SELLERS
     =================================================================================================================*/
    Route::post('sellers/datatable', 'SellersController@datatable')
        ->name('admin.sellers.datatable');

    Route::post('sellers/selected/destroy', 'SellersController@massDestroy')
        ->name('admin.sellers.selected.destroy');

    Route::post('sellers/{id}/remote-login', 'SellersController@remoteLogin')
        ->name('admin.sellers.remote-login');

    Route::get('sellers/{id}/remote-logout', 'HomeController@remoteLogout')
        ->name('admin.sellers.remote-logout');

    Route::resource('sellers', 'SellersController', [
        'as' => 'admin',
        'except' => ['show']]);

    //ROLES & PERMISSIONS
    Route::resource('roles', 'RolesController', [
                    'as' => 'admin', 
                    'except' => ['create', 'edit']]);


    /*=================================================================================================================
     * SETTINGS
     =================================================================================================================*/
    Route::post('settings/storage/clean', 'SettingsController@storageClean')
        ->name('admin.settings.storage.clean');

    Route::get('settings/directory/show', 'SettingsController@showDirectory')
        ->name('admin.settings.directory.show');

    Route::delete('settings/directory/clean', 'SettingsController@cleanDirectory')
        ->name('admin.settings.directory.clean');

    Route::get('settings/file/download', 'SettingsController@downloadFile')
        ->name('admin.settings.file.download');

    Route::post('settings/file/destroy', 'SettingsController@destroyFile')
        ->name('admin.settings.file.destroy');

    Route::post('settings/directory/compact', 'SettingsController@compactDirectory')
        ->name('admin.settings.directory.compact');

    Route::post('settings/directory/load', 'SettingsController@loadDirectories')
        ->name('admin.settings.directory.load');

    Route::resource('settings', 'SettingsController', [
                    'as' => 'admin',
                    'only' => ['index', 'store']]);

    
    /*=================================================================================================================
     * STATUS
     =================================================================================================================*/
    Route::post('status/datatable', 'StatusController@datatable')
        ->name('admin.status.datatable');

    Route::post('status/selected/destroy', 'StatusController@massDestroy')
        ->name('admin.status.selected.destroy');

    Route::get('status/sort', 'StatusController@sortEdit')
        ->name('admin.status.sort');

    Route::post('status/sort', 'StatusController@sortUpdate')
        ->name('admin.status.sort.update');

    Route::resource('status', 'StatusController', [
        'as' => 'admin',
        'except' => ['show']]);

    /*=================================================================================================================
     * PAYMENT STATUS
     =================================================================================================================*/
    Route::post('paymentstatus/datatable', 'PaymentStatusController@datatable')
        ->name('admin.paymentstatus.datatable');

    Route::post('paymentstatus/selected/destroy', 'PaymentStatusController@massDestroy')
        ->name('admin.paymentstatus.selected.destroy');

    Route::get('paymentstatus/sort', 'PaymentStatusController@sortEdit')
        ->name('admin.paymentstatus.sort');

    Route::post('paymentstatus/sort', 'PaymentStatusController@sortUpdate')
        ->name('admin.paymentstatus.sort.update');

    Route::resource('paymentstatus', 'PaymentStatusController', [
        'as' => 'admin',
        'except' => ['show']]);
    
    /*=================================================================================================================
     * PAYMENT TYPES
     =================================================================================================================*/
    Route::post('paymenttypes/datatable', 'PaymentTypesController@datatable')
        ->name('admin.paymenttypes.datatable');

    Route::post('paymenttypes/selected/destroy', 'PaymentTypesController@massDestroy')
        ->name('admin.paymenttypes.selected.destroy');

    Route::get('paymenttypes/sort', 'PaymentTypesController@sortEdit')
        ->name('admin.paymenttypes.sort');

    Route::post('paymenttypes/sort', 'PaymentTypesController@sortUpdate')
        ->name('admin.paymenttypes.sort.update');

    Route::resource('paymenttypes', 'PaymentTypesController', [
        'as' => 'admin',
        'except' => ['show']]);


    /*=================================================================================================================
     * PAYMENTS
     =================================================================================================================*/
    Route::post('payments/datatable', 'PaymentsController@datatable')
        ->name('admin.payments.datatable');

    Route::post('payments/selected/destroy', 'PaymentsController@massDestroy')
        ->name('admin.payments.selected.destroy');

    Route::get('payments/sort', 'PaymentsController@sortEdit')
        ->name('admin.payments.sort');

    Route::post('payments/sort', 'PaymentsController@sortUpdate')
        ->name('admin.payments.sort.update');
    
    Route::get('payments/statusEdit/{id}', 'PaymentsController@statusEdit')
        ->name('admin.payments.statusEdit');
    
    Route::get('payments/{id}/payed','PaymentsController@payed')
        ->name('admin.payments.payed');

    Route::resource('payments', 'PaymentsController', [
        'as' => 'admin',
        'except' => ['show']]);
    /*=================================================================================================================
     * PRODUCTS
     =================================================================================================================*/
    Route::post('products/datatable', 'ProductsController@datatable')
        ->name('admin.products.datatable');

    Route::post('products/selected/destroy', 'ProductsController@massDestroy')
        ->name('admin.products.selected.destroy');

    Route::get('products/sort', 'ProductsController@sortEdit')
        ->name('admin.products.sort');

    Route::post('products/sort', 'ProductsController@sortUpdate')
        ->name('admin.products.sort.update');
    
    Route::get('products/updateCategory/{id}','ProductsController@updateCategory');
    Route::get('products/updateCategoryBySeller/{id}','ProductsController@updateCategoryBySeller');
            
    Route::resource('products', 'ProductsController', [
        'as' => 'admin',
        'except' => ['show']]);
    
    
    /*=================================================================================================================
     * EXTRA PRODUCTS
     =================================================================================================================*/
    Route::post('extraproducts/datatable', 'ExtraProductsController@datatable')
        ->name('admin.extraproducts.datatable');

    Route::post('extraproducts/selected/destroy', 'ExtraProductsController@massDestroy')
        ->name('admin.extraproducts.selected.destroy');

    Route::get('extraproducts/sort', 'ExtraProductsController@sortEdit')
        ->name('admin.extraproducts.sort');

    Route::post('extraproducts/sort', 'ExtraProductsController@sortUpdate')
        ->name('admin.extraproducts.sort.update');

    Route::resource('extraproducts', 'ExtraProductsController', [
        'as' => 'admin',
        'except' => ['show']]);
        
    /*=================================================================================================================
     * CATEGORIES
     =================================================================================================================*/
    Route::post('categories/datatable', 'CategoriesController@datatable')
        ->name('admin.categories.datatable');

    Route::post('categories/selected/destroy', 'CategoriesController@massDestroy')
        ->name('admin.categories.selected.destroy');

    Route::get('categories/sort', 'CategoriesController@sortEdit')
        ->name('admin.categories.sort');

    Route::post('categories/sort', 'CategoriesController@sortUpdate')
        ->name('admin.categories.sort.update');

    Route::resource('categories', 'CategoriesController', [
        'as' => 'admin',
        'except' => ['show']]);
    
    
    /*=================================================================================================================
     * SUBCATEGORIES
     =================================================================================================================*/
    Route::post('subcategories/datatable', 'SubCategoriesController@datatable')
        ->name('admin.subcategories.datatable');

    Route::post('subcategories/selected/destroy', 'SubCategoriesController@massDestroy')
        ->name('admin.subcategories.selected.destroy');

    Route::get('subcategories/sort', 'SubCategoriesController@sortEdit')
        ->name('admin.subcategories.sort');

    Route::post('subcategories/sort', 'SubCategoriesController@sortUpdate')
        ->name('admin.subcategories.sort.update');
    
    Route::get('subcategories/updateCategory/{id}','SubcategoriesController@updateCategory');

    Route::resource('subcategories', 'SubCategoriesController', [
        'as' => 'admin',
        'except' => ['show']]);
    
    /*=================================================================================================================
     * ADDRESSSES
     =================================================================================================================*/
    Route::post('addresses/datatable', 'AddressesController@datatable')
        ->name('admin.addresses.datatable');

    Route::post('addresses/selected/destroy', 'AddressesController@massDestroy')
        ->name('admin.addresses.selected.destroy');

    Route::get('addresses/sort', 'AddressesController@sortEdit')
        ->name('admin.addresses.sort');

    Route::post('addresses/sort', 'AddressesController@sortUpdate')
        ->name('admin.addresses.sort.update');

    Route::resource('addresses', 'AddressesController', [
        'as' => 'admin',
        'except' => ['show']]);
    

            
    /*=================================================================================================================
     * ORDERLINES
     =================================================================================================================*/
    Route::post('orderlines/datatable', 'OrderLinesController@datatable')
        ->name('admin.orderlines.datatable');

    Route::post('orderlines/selected/destroy', 'OrderLinesController@massDestroy')
        ->name('admin.orderlines.selected.destroy');

    Route::get('orderlines/sort', 'OrderLinesController@sortEdit')
        ->name('admin.orderlines.sort_status');

    Route::post('orderlines/sort', 'OrderLinesController@sortUpdate')
        ->name('admin.orderlines.sort.update');

    Route::get('orderlines/updatePriceVat/{id}/{quantity}','OrderLinesController@updatePriceVat');

    Route::resource('orderlines', 'OrderLinesController', [
        'as' => 'admin',
        'except' => ['show']]);
    
    
    /*=================================================================================================================
     * ORDERS
     =================================================================================================================*/
    Route::post('orders/datatable', 'OrdersController@datatable')
        ->name('admin.orders.datatable');

    Route::post('orders/selected/destroy', 'OrdersController@massDestroy')
        ->name('admin.orders.selected.destroy');

    Route::get('orders/sort', 'OrdersController@sortEdit')
        ->name('admin.orders.sort');

    Route::post('orders/sort', 'OrdersController@sortUpdate')
        ->name('admin.orders.sort.update');

    Route::post('orders/selected/addOrderLines', 'OrdersController@addOrderLines')
        ->name('admin.orders.selected.addOrderLines');
    
    Route::post('orders/createOrder', 'OrdersController@createOrder')
        ->name('admin.orders.createOrder');

    Route::resource('orders', 'OrdersController', [
        'as' => 'admin',
        'except' => ['show']]);
    /*=================================================================================================================
     * MANAGE LOGS
     =================================================================================================================*/
    Route::get('logs/errors/{filename}/download', 'LogViewerController@download', ['as' => 'admin'])
        ->name('admin.logs.errors.download');

    Route::post('logs/errors/destroy/all', 'LogViewerController@destroyAll', ['as' => 'admin'])
        ->name('admin.logs.errors.destroy.all');

    Route::resource('logs/errors', 'LogViewerController', [
        'as'    => 'admin.logs',
        'only'  => ['index', 'destroy']]);

    /*=================================================================================================================
     * TEST CONTROLLER
     =================================================================================================================*/
    Route::get('test', 'TestController@index')
        ->name('admin.test.index');
});