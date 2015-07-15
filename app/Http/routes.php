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
				Route::post('draft', 'Jobs\TaskController@draft');
				Route::get('taskform/{taskid?}', 'Jobs\TaskController@taskform')->where('taskid', '[0-9]+');;
				Route::get('accept/task/{taskid}', 'Jobs\TaskController@acceptTask')->where('taskid', '[0-9]+');
				Route::get('accept/othertask/{taskid}', 'Jobs\TaskController@acceptOtherTask')->where('taskid', '[0-9]+');
				Route::post('reject/task/{taskid}', 'Jobs\TaskController@rejectTask')->where('taskid', '[0-9]+');
				Route::post('reject/othertask/{taskid}', 'Jobs\TaskController@rejectOtherTask')->where('taskid', '[0-9]+');
				Route::post('task/{taskid}/comment', 'Jobs\TaskController@comment')->where('taskid', '[0-9]+');
				Route::post('task/update/{taskid}', 'Jobs\TaskController@rejectTask')->where('taskid', '[0-9]+');
				Route::get('task/{taskid}', 'Jobs\TaskController@viewTask')->where('taskid', '[0-9]+');
				Route::get('othertask/{taskid}', 'Jobs\TaskController@viewOtherTask')->where('taskid', '[0-9]+');
				Route::post('status/{taskid}', 'Jobs\TaskController@updateStatus')->where('taskid', '[0-9]+');
				Route::get('followup/{taskid}', 'Jobs\TaskController@viewFollowup')->where('taskid', '[0-9]+');
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
				Route::get('load/{temMeetingId}','Meetings\MeetingsController@loadMeeting')->where('id', '[0-9]+');
				Route::post('update/{temMeetingId}','Meetings\MeetingsController@updateMeeting')->where('id', '[0-9]+');
			});
			Route::group(['prefix' => 'minute'], function()
			{
				Route::get('{meetingId}/{minuteId?}', 'Meetings\MinuteController@index')->where('meetingId', '[0-9]+')->where('minuteId', '[0-9]+');
				Route::get('followup/{taskid}', 'Meetings\TaskController@viewFollowup')->where('minueId', '[0-9]+')->where('taskid', '[0-9]+');
				Route::get('view/{minuteId}', 'Meetings\MinuteController@viewMinute')->where('minueId', '[0-9]+');
				Route::get('accept/task/{taskid}', 'Meetings\TaskController@acceptTask')->where('taskid', '[0-9]+');
				Route::get('accept/othertask/{taskid}', 'Meetings\TaskController@acceptOtherTask')->where('taskid', '[0-9]+');
				Route::post('reject/task/{taskid}', 'Meetings\TaskController@rejectTask')->where('taskid', '[0-9]+');
				Route::post('reject/othertask/{taskid}', 'Meetings\TaskController@rejectOtherTask')->where('taskid', '[0-9]+');
				Route::post('task/{taskid}/comment', 'Meetings\TaskController@comment')->where('taskid', '[0-9]+');
				Route::get('task/{taskid}', 'Meetings\TaskController@viewTask')->where('taskid', '[0-9]+');
				Route::get('othertask/{taskid}', 'Meetings\TaskController@viewOtherTask')->where('taskid', '[0-9]+');
				Route::post('status/{taskid}', 'Meetings\TaskController@updateStatus')->where('taskid', '[0-9]+');
				Route::post('{meetingId}', 'Meetings\MinuteController@create')->where('meetingId', '[0-9]+');
				Route::post('{minuteId}/update', 'Meetings\MinuteController@update')->where('minueId', '[0-9]+');
				Route::post('{minuteId}/draft', 'Meetings\MinuteController@draft')->where('minueId', '[0-9]+');
				Route::post('{minuteId}/task', 'Meetings\TaskController@createTask')->where('minueId', '[0-9]+');
			});
		
		});
		
	});	