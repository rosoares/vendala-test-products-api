<?php

use Illuminate\Http\Request;
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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'API\Auth\AuthController@login');
    Route::post('logout', 'API\Auth\AuthController@logout');
    Route::post('refresh', 'API\Auth\AuthController@refresh');
    Route::post('me', 'API\Auth\AuthController@me');

});

Route::resource('users', 'API\Users\UserController')->except(['edit', 'create', 'index']);
Route::resource('products', 'API\Products\ProductController')->except(['edit', 'create']);