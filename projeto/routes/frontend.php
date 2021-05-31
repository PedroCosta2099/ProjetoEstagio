<?php

Route::group(array('prefix' => LaravelLocalization::setLocale(), 'namespace' => 'Customer'), function() {       
    
    Route::get('/customer/products','ProductsController@listProducts')
            ->name('products.index');
    Route::get('/','HomeController@index')
            ->name('home.index');

});