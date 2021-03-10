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
    //Boards
    Route::resource('boards', 'BoardController');
    //Lists
    Route::get('/boards/{board}/lists','ListController@index');
    Route::post('/boards/{board}/lists','ListController@store');
    Route::put('/boards/{board}/lists/{list}','ListController@update');
    Route::delete('/boards/{board}/lists/{list}','ListController@destroy');
    Route::post('/sort_list','ListController@sortList');
});
