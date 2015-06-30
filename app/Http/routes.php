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
			Route::get('user/add', 'Admin\UserController@getAdd');
			Route::post('user/add', 'Admin\UserController@postAdd');
			Route::get('meetings', 'Admin\MeetingsController@index');
			Route::post('meetings/approve', 'Admin\MeetingsController@approve');
			Route::post('meetings/disapprove', 'Admin\MeetingsController@disapprove');
		});
	});
    Route::get('auth/register', 'Admin\AuthController@signupGet');
	Route::post('auth/register', 'Admin\AuthController@signupPost');
	Route::get('auth/login', 'Admin\AuthController@loginGet');
	Route::post('auth/login', 'Admin\AuthController@loginPost');
	Route::get('auth/logout', 'Admin\AuthController@logout');
});

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
			Route::get('/', 'Jobs\TaskController@index');
			Route::get('user/search', 'Auth\ProfileController@findUser');	
			Route::group(['prefix' => 'jobs'], function()
			{
				Route::get('/', 'Jobs\TaskController@index');
				Route::get('mytask', 'Jobs\TaskController@mytask');
				Route::get('followups', 'Jobs\TaskController@followups');
				Route::get('history', 'Jobs\TaskController@history');
				Route::post('createTask','Jobs\TaskController@createTask');
			});
			Route::group(['prefix' => 'meetings'], function()
			{
				Route::get('/', 'Meetings\MeetingsController@index');
				Route::get('myminutes', 'Meetings\MeetingsController@myminutes');
				Route::get('history', 'Meetings\MeetingsController@history');
				Route::post('create','Meetings\MeetingsController@createMeeting');
				Route::get('load/{temMeetingId}','Meetings\MeetingsController@loadMeeting');
				Route::post('update/{temMeetingId}','Meetings\MeetingsController@updateMeeting');
			});
			Route::group(['prefix' => 'minute'], function()
			{
				Route::get('{meetingID}/{minuteID?}', 'Meetings\MinuteController@index');
				Route::post('{meetingID}', 'Meetings\MinuteController@create');
				Route::post('{minuteID}/update', 'Meetings\MinuteController@update');
				Route::post('{minuteID}/draft', 'Meetings\MinuteController@draft');
				Route::post('{minuteID}/task', 'Meetings\TaskController@createTask');
			});
		
		});
		
	});	