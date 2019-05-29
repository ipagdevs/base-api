<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group([], function () {
    Route::post('auth', 'AuthController@authenticate');
    Route::post('register', 'AuthController@register');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('user', 'AuthController@getUser');
    Route::resource('products', 'ProductController');
});
