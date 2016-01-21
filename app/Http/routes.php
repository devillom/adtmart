<?php

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/oauth/{provider}/redirect', 'Auth\AuthManagerController@oauthLogin')->name('oAuthRedirect');
    Route::get('/oauth/{provider}/callback', 'Auth\AuthManagerController@oauthHandle');
    Route::get('/oauth/email', 'Auth\AuthManagerController@getEmail')->name('oAuthGetEmail');
    Route::post('/oauth/email', 'Auth\AuthManagerController@setEmail')->name('oAuthSetEmail');
    Route::get('/', 'HomeController@index');
});
