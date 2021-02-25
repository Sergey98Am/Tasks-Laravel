<?php

Route::group([
    'prefix' => 'auth',
    'namespace' => 'Auth'
], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');
});

Route::middleware('jwt')->group(function () {
    Route::get('me', 'Auth\AuthController@me');
});
