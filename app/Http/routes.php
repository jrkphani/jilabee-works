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

/*Route::get('/', 'WelcomeController@index');*/
Route::group(['prefix' => 'admin'], function()
{
	Route::group(['middleware' => 'adminOnly'], function()
	{
		Route::group(['middleware' => 'checkDatabase'], function()
		{
			Route::get('/', function(){
				return view('admin.dashboard');
			});
		});
	});
    Route::get('auth/register', 'Admin\AuthController@signupGet');
	Route::post('auth/register', 'Admin\AuthController@signupPost');
	Route::get('auth/login', 'Admin\AuthController@loginGet');
	Route::post('auth/login', 'Admin\AuthController@loginPost');
	Route::get('auth/logout', 'Admin\AuthController@logout');
});
	/*Route::group(['domain' => 'admin.localjotter.com'], function()
	{
		 DB::disconnect();
	     Config::set('database.default','base');
	     DB::reconnect();
	    configureConnection('jotterBase');	    
	});*/	

	Route::controllers([
		'auth' => 'Auth\AuthController',
		'password' => 'Auth\PasswordController',
		]);
	
//uncomment  domain group routing after fixing multi domain seesoin works 
//Route::group(['domain' => 'app.localjotter.com'], function()
//{
	

    Route::group(['middleware' => 'auth'], function()
	{
		Route::group(['middleware' => 'checkDatabase'], function()
		{
			Route::get('/', function(){
			return view('user');
			});
		});
		
	});	