<?php

use Illuminate\Support\Facades\Route;

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
Route::group(['prefix' => 'v1'], function () {
    Route::post('login', 'V1\AuthController@login')->name('login');
    Route::group(['middleware' => 'jwt.auth.user'], function () {
        Route::post('logout', 'V1\AuthController@logout')->name('logout');
        Route::resource('details', 'V1\UserDetailController');
        Route::get('amount-details', 'V1\UserDetailController@listUserDetailsWithTotal')->name('account_details');
    });
});