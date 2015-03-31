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
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
	]);
//uncomment  domain group routing after fixing multi domain seesoin works 
//Route::group(['domain' => 'app.localjotter.com'], function()
//{
	

    Route::group(['middleware' => 'auth'], function()
	{
		//Route::get('home', 'HomeController@index');
		Route::get('profile/{id?}', 'ProfileController@index');
		Route::get('home', 'NotesController@index');
		
		Route::get('minute', 'MinutesController@list_minutes');
		Route::get('minute/add', 'MinutesController@getAdd');
		Route::post('minute/add', 'MinutesController@postAdd');

		Route::get('notes', 'NotesController@index');
		
		
		Route::get('followup', 'NotesController@followup');

		// Route::get('minute', 'MinutesController@list_minutes');
		// Route::get('minute/{id}', 'MinutesController@list_history')->where('id', '[0-9]+');

		
		
		Route::group(['middleware' => 'onlyajax'], function()
		{
			Route::get('minutehistory/{id}', 'MinutesHistoryController@list_history')->where('id', '[0-9]+');;
			Route::get('minutehistory/add/{id}', 'MinutesHistoryController@getAdd')->where('id', '[0-9]+');;
			Route::post('minutehistory/add/{id}', 'MinutesHistoryController@postAdd')->where('id', '[0-9]+');;
			Route::post('comments/add/{id}', 'NotesController@postComment')->where('id', '[0-9]+');
			//Route::get('minute/{id}', 'MinutesController@list_history')->where('id', '[0-9]+');
			Route::get('notes/{id?}', 'NotesController@index')->where('id', '[0-9]+');
			Route::post('notes/draft/{id}', 'NotesController@postDraft')->where('id', '[0-9]+');
			Route::post('notes/add/{id}', 'NotesController@postAdd')->where('id', '[0-9]+');
			Route::get('notes/add/{id}', 'NotesController@getAdd')->where('id', '[0-9]+');

			Route::get('user/search', 'ProfileController@findUser');
		});
	});

//});