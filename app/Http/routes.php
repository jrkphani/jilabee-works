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

	Route::group(['domain' => 'admin.localjotter.com'], function()
	{
		print_r(Auth::user());
		print_r(Session::get('blabla','dd'));
		if(DB::connection()->getDatabaseName())
		{
		   echo "conncted sucessfully to database ".DB::connection()->getDatabaseName();
		}

		// DB::disconnect();
	 //    Config::set('database.default','base');
	 //    DB::reconnect();
	    //configureConnection('jotterBase');

	    Route::get('auth/register', 'Admin\AuthController@signupGet');
		Route::post('auth/register', 'Admin\AuthController@signupPost');
		Route::get('auth/login', 'Admin\AuthController@loginGet');
		Route::post('auth/login', 'Admin\AuthController@loginPost');
		Route::get('auth/logout', 'Admin\AuthController@logout');
	});	

	Route::get('/', 'Auth\AuthController@getRegister');

	Route::controllers([
		'auth' => 'Auth\AuthController',
		'password' => 'Auth\PasswordController',
		]);
	
//uncomment  domain group routing after fixing multi domain seesoin works 
//Route::group(['domain' => 'app.localjotter.com'], function()
//{
	

    Route::group(['middleware' => 'auth'], function()
	{
		Route::get('/', function(){
			return view('user');
		});
	});


		