<?php


Route::group(array('prefix' => LaravelLocalization::setLocale(), 'namespace' => 'Customer'), function() {       
    
    Route::get('/products','ProductsController@listProducts')
            ->name('products.index');
    Route::get('/','HomeController@index')
            ->name('home.index');
    Route::get('/products/{id}/show','ProductsController@showProduct')
            ->name('customer.products.productShow');

            Route::get('test',function()
        {
                Cart::add('293ad', 'Product 1', 1, 9.99, ['size' => 'large']);
        });

        Route::get('cart',function()
        {
                Cart::content();
        });

});