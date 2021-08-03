<?php

use Syscover\ShoppingCart\Item;
use Syscover\ShoppingCart\TaxRule;

   Route::get('/feed','HomeController@index')
        ->name('home.index');
          
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
Route::group(array('prefix' => 'register', 'middleware' => 'guest', 'namespace' => 'Auth'), function() {

        Route::get('/','RegisterController@index')
                ->name('customer.register');
        Route::post('/submit','RegisterController@create')
                ->name('customer.register.submit');
    });

 


    //LOGOUT
Route::get('customer/logout', 'Auth\LoginController@logout')
->name('customer.logout')
->middleware('auth');

/*=================================================================================================================
 *              PRODUCTS
 =================================================================================================================*/
 Route::get('/products','Customer\ProductsController@listProducts')
 ->name('customer.products.index');
 
 
Route::get('/products/{id}/show','Customer\ProductsController@showProduct')
 ->name('customer.products.productShow');

 Route::get('/cart','Customer\CartController@cartItems')
             ->name('customer.cart.index');
        Route::get('/cart/insert/{id}/{quantity}','Customer\CartController@addToCart')
                ->name('customer.cart.addToCart');
        Route::get('customer/cart/updatePrice/{rowId}/{id}/{quantity}','Customer\CartController@updatePrice');


/*=================================================================================================================
 *              CUSTOMER GLOBAL ROUTES 
 =================================================================================================================*/            
Route::group(array('middleware' => 'auth','namespace' => 'Customer'), function() {  

 
 
/*=================================================================================================================
 *              ABOUT
 =================================================================================================================*/
        Route::get('/about','HomeController@about')
                ->name('customer.about');
        Route::get('/test','HomeController@index')
                ->name('customer.test');
        Route::get('/orderStatus/{id}','HomeController@orderStatus')
                ->name('customer.orderStatus');
/*=================================================================================================================
 *              CART
 =================================================================================================================*/
     
        
        Route::get('/cart/destroyRow/{rowId}}','CartController@destroyRow')
                ->name('customer.cart.destroyRow');
        Route::get('/cart/cleanCart','CartController@cleanCart')
                ->name('customer.cart.cleanCart');
        Route::get('cart/finalizeOrder','CartController@createOrder')
                ->name('customer.cart.finalizeOrder');
        Route::get('cart/orderInfo','CartController@orderInfo')
                ->name('customer.cart.payment');
        Route::get('cart/paymentMethod/{id}','CartController@savePaymentMethod')
                ->name('customer.cart.paymentMethod');
        Route::get('cart/resumeOrder','CartController@resumeOrder')
                ->name('customer.cart.resumeOrder');
        Route::get('cart/deleteCartAndPayment','CartController@deleteCartAndPayment')
                ->name('customer.cart.deleteCartAndPayment');
});

/*=================================================================================================================
 *              SELLERS
 =================================================================================================================*/
 Route::get('/store/{name}','Customer\SellersController@index')
        ->name('customer.seller');