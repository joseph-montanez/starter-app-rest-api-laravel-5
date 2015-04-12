<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

//-- Unprotected API Routes
Route::group(['middleware' => 'nice.errors', 'namespace' => 'Api', 'prefix' => 'api/v1'], function()
{
    Route::post('token/generate', 'TokenController@generate');
    Route::get('token/authorize', 'TokenController@authorize');

    Route::post('user/register', 'UserController@register');
    Route::post('user/login', 'UserController@login');
});

//-- Protected API Routes
Route::group(['middleware' => ['nice.errors', 'auth.token'], 'namespace' => 'Api', 'prefix' => 'api/v1'], function()
{
    Route::post('user', 'UserController@user');
    Route::post('user/forgot-password', 'UserController@forgotPassword');
});

Route::get('profile', [
    'middleware' => 'auth',
    'uses' => 'UserController@showProfile'
]);