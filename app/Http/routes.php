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
			Route::get('user/list', 'Admin\UserController@userList');
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
				Route::get('/task/{taskid}', 'Jobs\TaskController@viewTask');
				Route::get('/followup/{taskid}', 'Jobs\TaskController@viewFollowup');
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
				Route::get('{meetingId}/{minuteId?}', 'Meetings\MinuteController@index');
				Route::get('{minueId}/followup/{taskid}', 'Meetings\MinuteController@viewFollowup');
				Route::get('{minueId}/task/{taskid}', 'Meetings\MinuteController@viewTask');
				Route::post('{meetingId}', 'Meetings\MinuteController@create');
				Route::post('{minuteId}/update', 'Meetings\MinuteController@update');
				Route::post('{minuteId}/draft', 'Meetings\MinuteController@draft');
				Route::post('{minuteId}/task', 'Meetings\TaskController@createTask');
			});
		
		});
		
	});	