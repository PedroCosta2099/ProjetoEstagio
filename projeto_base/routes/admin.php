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
     * VEHICLES
     =================================================================================================================*/
    Route::post('vehicles/datatable', 'VehiclesController@datatable')
        ->name('admin.vehicles.datatable');

    Route::post('vehicles/selected/destroy', 'VehiclesController@massDestroy')
        ->name('admin.vehicles.selected.destroy');

    Route::get('vehicles/sort', 'VehiclesController@sortEdit')
        ->name('admin.vehicles.sort');

    Route::post('vehicles/sort', 'VehiclesController@sortUpdate')
        ->name('admin.vehicles.sort.update');

    Route::resource('vehicles', 'VehiclesController', [
        'as' => 'admin',
        'except' => ['show']]);

    /*=================================================================================================================
     * BRANDS
     =================================================================================================================*/
    Route::post('brands/datatable', 'BrandsController@datatable')
        ->name('admin.brands.datatable');

    Route::post('brands/selected/destroy', 'BrandsController@massDestroy')
        ->name('admin.brands.selected.destroy');

    Route::get('brands/sort', 'BrandsController@sortEdit')
        ->name('admin.brands.sort');

    Route::post('brands/sort', 'BrandsController@sortUpdate')
        ->name('admin.brands.sort.update');

    Route::resource('brands', 'BrandsController', [
        'as' => 'admin',
        'except' => ['show']]);
    
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