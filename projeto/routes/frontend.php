<?php

use Syscover\ShoppingCart\Item;
use Syscover\ShoppingCart\TaxRule;


/*=================================================================================================================
 * LOGIN & RESET PASSWORD
 =================================================================================================================*/
//LOGIN
Route::group(array('prefix' => 'login', 'middleware' => 'guest', 'namespace' => 'Auth'), function() {

        Route::get('/', 'LoginController@index')
                ->name('customer.login');
    
        Route::post('/', 'LoginController@login')
                ->name('customer.login.submit');
    });
   
    Route::get('/','HomeController@index')
    ->name('home.index');
    //LOGOUT
Route::get('customer/logout', 'Auth\LoginController@logout')
->name('customer.logout')
->middleware('auth');
Route::group(array('middleware' => 'auth','namespace' => 'Customer'), function() {  
        
     
        /**
         * PRODUCTS
         */
        Route::get('/products','ProductsController@listProducts')
            ->name('customer.products.index');
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
        Route::get('cart/deleteCartAndPayment','CartController@deleteCartAndPayment')
                ->name('customer.cart.deleteCartAndPayment');
});