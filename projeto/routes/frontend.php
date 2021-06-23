<?php

use Syscover\ShoppingCart\Item;
use Syscover\ShoppingCart\TaxRule;

Route::group(array('prefix' => LaravelLocalization::setLocale(), 'namespace' => 'Customer'), function() {       
        /**
         * PRODUCTS
         */
        Route::get('/products','ProductsController@listProducts')
            ->name('customer.products.index');
        Route::get('/','HomeController@index')
            ->name('home.index');
        Route::get('/products/{id}/show','ProductsController@showProduct')
            ->name('customer.products.productShow');

        /**
         * CART
         */
        Route::get('/cart','CartController@cartItems')
             ->name('customer.cart.index');
        Route::get('/cart/insert/{id}/{quantity}','CartController@addToCart')
                ->name('customer.cart.addToCart');
        Route::get('customer/cart/updatePrice/{rowId}/{id}/{quantity}','CartController@updatePrice');
        
        Route::get('/cart/destroyRow/{rowId}}','CartController@destroyRow')
                ->name('customer.cart.destroyRow');
        Route::get('/cart/cleanCart','CartController@cleanCart')
                ->name('customer.cart.cleanCart');
        Route::get('cart/finalizeOrder','CartController@createOrder')
                ->name('customer.cart.finalizeOrder');
        Route::get('cart/payment','CartController@paymentMethod')
                ->name('customer.cart.payment');
        Route::get('cart/paymentMethod/{id}','CartController@savePaymentMethod')
                ->name('customer.cart.paymentMethod');
        Route::get('cart/resumeOrder','CartController@resumeOrder')
                ->name('customer.cart.resumeOrder');
});