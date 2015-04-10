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
		
		Route::bind('meetingid', function($meetingid){
				return App\Model\Meetings::find($meetingid);
			});
		Route::bind('minuteid', function($minuteid){
				return App\Model\Minutes::find($minuteid);
			});
		Route::bind('taskid', function($taskid){
				return App\Model\Tasks::find($taskid);
			});
		
		Route::group(['middleware' => 'admin'], function()
		{
			Route::get('userlist', 'ProfileController@userlist');
			Route::get('meeting/add', 'MeetingsController@getAdd');
			Route::post('meeting/add', 'MeetingsController@postAdd');
			Route::get('meeting/{meetingid}/edit', 'MeetingsController@getEdit')->where('id', '[0-9]+');;
			Route::post('meeting/{meetingid}/edit', 'MeetingsController@postEdit')->where('id', '[0-9]+');;

			Route::bind('userid', function($uid){
				return App\User::find($uid);
			});
			Route::get('user/{userid}/edit', 'ProfileController@getuser');
			Route::post('user/{userid}/edit', 'ProfileController@postuser');
		});

		Route::group(['middleware' => 'onlyajax'], function()
		{
			Route::get('mytask', 'TasksController@mytask');

			Route::get('followup', 'TasksController@followup');

			Route::get('meetings', 'MeetingsController@index');

			Route::get('user/search', 'ProfileController@findUser');
			
			Route::get('task/{taskid}/edit', 'TasksController@edit');
			Route::post('task/{taskid}/edit', 'TasksController@update');

			Route::post('task/{tid}/reject', 'TasksController@reject');
			Route::post('task/{tid}/accept', 'TasksController@accept');

			Route::get('task/{taskid}/comments', 'TasksController@getComment');
			Route::post('task/{taskid}/comments/add', 'TasksController@postComment');

			Route::get('minute/{minuteid}/tasks', 'TasksController@index')->where('id', '[0-9]+');
			Route::post('minute/{minuteid}/tasks/add/draft', 'TasksController@postDraft')->where('id', '[0-9]+');

			Route::get('stickynotes', 'SticknotesController@index');
			Route::get('stickynotes/remove/{id}', 'SticknotesController@remove')->where('id', '[0-9]+');;
			Route::post('stickynotes', 'SticknotesController@postData');
		});

		Route::get('profile/{id?}', 'ProfileController@index');
		
		Route::get('meeting/{meetingid}/nextminute', 'MinutesController@getAdd')->where('id', '[0-9]+');;
		Route::post('meeting/{meetingid}/nextminute', 'MinutesController@postAdd')->where('id', '[0-9]+');;
		
		
		Route::get('minute/{minuteid}/tasks/add', 'TasksController@getAdd')->where('id', '[0-9]+');
		Route::post('minute/{minuteid}/tasks/add', 'TasksController@postAdd')->where('id', '[0-9]+');
		
	});


		