<?php

use Syscover\ShoppingCart\Item;
use Syscover\ShoppingCart\TaxRule;

Route::group(array('prefix' => LaravelLocalization::setLocale(), 'namespace' => 'Customer'), function() {       
    
        Route::get('/products','ProductsController@listProducts')
            ->name('customer.products.index');
        Route::get('/','HomeController@index')
            ->name('home.index');
        Route::get('/products/{id}/show','ProductsController@showProduct')
            ->name('customer.products.productShow');
        Route::get('/cart','CartController@cartItems')
             ->name('customer.cart.index');
        Route::get('/cart/insert/{id}','CartController@addToCart')
                ->name('customer.cart.addToCart');
});