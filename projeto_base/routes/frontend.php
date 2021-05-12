<?php


Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [ 'localeSessionRedirect', 'localizationRedirect' ]], function() {
        
    Route::get('/', 'HomeController@index')
        ->name('home.index');
});