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
Route::group(['domain' => 'app.localjotter.com'], function()
{
	Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
	]);

    Route::group(['middleware' => 'auth'], function()
	{
		//Route::get('home', 'HomeController@index');
		Route::get('home', 'MeetingController@index');
	});

});