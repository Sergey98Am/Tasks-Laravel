<?php

Route::group([
    'prefix' => 'auth',
    'namespace' => 'Auth',
    'middleware' => 'guest'
], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('forgot-password', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('reset-password', 'ResetPasswordController@reset');
    Route::get('/email/resend', 'VerificationController@resend')->name('verification.resend');
    Route::get('/email-verification', 'VerificationController@verify');
    Route::get('/authorize/{provider}/redirect', 'SocialAuthController@redirectToProvider');
    Route::get('/authorize/{provider}/callback', 'SocialAuthController@handleProviderCallback');
});

Route::middleware('jwt')->group(function () {
    Route::post('/check-token','Auth\AuthController@checkToken');
    Route::get('logout', 'Auth\AuthController@logout');
    Route::post('/change-details', 'UserController@changeDetails');
    //Boards
    Route::resource('boards', 'BoardController');
    //Lists
    Route::get('/boards/{board}/lists','ListController@index');
    Route::post('/boards/{board}/lists','ListController@store');
    Route::put('/boards/{board}/lists/{list}','ListController@update');
    Route::delete('/boards/{board}/lists/{list}','ListController@destroy');
    Route::post('/sort_list','ListController@sortList');
    //Cards
    Route::post('lists/{list}/cards','CardController@store');
    Route::put('lists/{list}/cards/{card}','CardController@update');
    Route::delete('lists/{list}/cards/{card}','CardController@destroy');
    Route::post('/sort_card','CardController@sortCard');
    Route::post('/move_card_to_another_list/{card}','CardController@moveCardToAnotherList');
    //Admin
    Route::resource('roles', 'Admin\UserManagement\RoleController');
    Route::get('abilities', 'Admin\UserManagement\RoleController@abilities');
    Route::resource('permissions', 'Admin\UserManagement\PermissionController');
    Route::resource('users', 'Admin\UserManagement\UserController');
});
