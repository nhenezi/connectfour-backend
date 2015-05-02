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

// Resources

Route::resource('game', 'GameController',
  ['only' => ['store', 'show', 'index', 'update']]
);
Route::resource('secret_key', 'SecretKeyController',
  ['only' => ['store', 'update']]
);
Route::get('auth/{access_token}', 'AuthController@get');
Route::post('auth', 'AuthController@post');

Route::post('user', 'UserController@post');
