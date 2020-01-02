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

Route::namespace('Api')->prefix('v1')->group(function (\Illuminate\Routing\Router $router){
    $router->post('login', 'v1\AuthController@login');
    $router->post('register', 'v1\AuthController@register');
});


Route::namespace('Api')->middleware('auth:api')->prefix('v1')->group(function (\Illuminate\Routing\Router $router){
    $router->post('logout', 'v1\AuthController@logout');
});