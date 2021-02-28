<?php

Route::group([
    'prefix' => 'auth',
    'namespace' => 'Auth'
], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
});

Route::middleware('jwt')->group(function () {
    Route::get('me', 'Auth\AuthController@me');
    Route::get('logout', 'Auth\AuthController@logout');
    Route::resource('boards', 'BoardController');
});
