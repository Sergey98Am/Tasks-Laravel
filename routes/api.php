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
    Route::post('/check-token', 'Auth\AuthController@checkToken');
    Route::get('logout', 'Auth\AuthController@logout');
    Route::post('/change-details', 'UserController@changeDetails');
    //Boards
    Route::resource('boards', 'BoardController');
    Route::get('/single-board/board/{board}', 'BoardController@singleBoard');
    // Lists
    Route::post('/boards/{board}/lists', 'ListController@store');
    Route::put('/boards/{board}/lists/{list}', 'ListController@update');
    Route::delete('/boards/{board}/lists/{list}', 'ListController@destroy');
    Route::post('/sort-list', 'ListController@sortList');
    // Cards
    Route::post('lists/{list}/cards', 'CardController@store');
    Route::put('lists/{list}/cards/{card}', 'CardController@update');
    Route::delete('lists/{list}/cards/{card}', 'CardController@destroy');
    Route::post('/sort-card', 'CardController@sortCard');
    Route::post('/move-card-to-another-list/{card}', 'CardController@moveCardToAnotherList');
    // Admin
    Route::resource('roles', 'Admin\UserManagement\RoleController');
    Route::get('abilities', 'Admin\UserManagement\RoleController@abilities');
    Route::resource('permissions', 'Admin\UserManagement\PermissionController');
    Route::resource('users', 'Admin\UserManagement\UserController');
    // Invite Member To Board
    Route::post('invitation/boards/{board}', 'InvitationController@inviteMember');
    Route::post('confirm-invitation/users/{user}/boards/{board}', 'InvitationController@confirmInvitation');
    // Comments
    Route::get('cards/{card}/comments', 'CommentController@index');
    Route::post('cards/{card}/comments', 'CommentController@store');
    Route::post('cards/{card}/comments/{comment}/replies', 'CommentController@reply');
    Route::put('cards/{card}/comments/{comment}', 'CommentController@update');
    Route::delete('cards/{card}/comments/{comment}', 'CommentController@destroy');
});
