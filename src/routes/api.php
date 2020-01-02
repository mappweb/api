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

Route::namespace('Mappweb\\Api\Http\\Controllers')->prefix('v1')->group(function (\Illuminate\Routing\Router $router){
    $router->post('login', 'Api\AuthController@login');
    $router->post('register', 'Api\AuthController@register');
});


Route::namespace('Mappweb\\Api\Http\\Controllers')->middleware('auth:api')->prefix('v1')->group(function (\Illuminate\Routing\Router $router){
    $router->post('logout', 'Api\AuthController@logout');
});