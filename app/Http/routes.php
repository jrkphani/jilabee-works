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
		//Route::group(['middleware' => 'checkDatabase'], function()
		//{
			Route::get('/', function(){
				return view('admin.dashboard');
			});
			Route::get('user/list', 'Admin\UserController@userList');
			Route::get('user/add', 'Admin\UserController@getAdd');
			Route::post('user/add', 'Admin\UserController@postAdd');
			Route::get('meetings', 'Admin\MeetingsController@index');
			Route::post('meetings/approve', 'Admin\MeetingsController@approve');
			Route::post('meetings/disapprove', 'Admin\MeetingsController@disapprove');
		//});
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
		//Route::group(['middleware' => 'checkDatabase'], function()
		//{
			Route::get('/', 'Jobs\TaskController@index');
			Route::get('user/search', 'Auth\ProfileController@findUser');	
			Route::group(['prefix' => 'jobs'], function()
			{
				Route::get('/', 'Jobs\TaskController@index');
				Route::post('draft', 'Jobs\TaskController@draft');
				Route::get('taskform/{taskid?}', 'Jobs\TaskController@taskform')->where('taskid', '[0-9]+');;
				Route::get('acceptTask/{taskid}', 'Jobs\TaskController@acceptTask')->where('taskid', '[0-9]+');
				Route::post('rejectTask/{taskid}', 'Jobs\TaskController@rejectTask')->where('taskid', '[0-9]+');
				Route::post('task/{taskid}/taskComment', 'Jobs\TaskController@taskComment')->where('taskid', '[0-9]+');
				Route::post('task/{taskid}/followupComment', 'Jobs\TaskController@followupComment')->where('taskid', '[0-9]+');
				Route::post('task/update/{taskid}', 'Jobs\TaskController@rejectTask')->where('taskid', '[0-9]+');
				Route::get('task/{taskid}', 'Jobs\TaskController@viewTask')->where('taskid', '[0-9]+');
				Route::get('markComplete/{taskid}', 'Jobs\TaskController@markComplete')->where('taskid', '[0-9]+');
				Route::get('acceptCompletion/{taskid}', 'Jobs\TaskController@acceptCompletion')->where('taskid', '[0-9]+');
				Route::get('rejectCompletion/{taskid}', 'Jobs\TaskController@rejectCompletion')->where('taskid', '[0-9]+');
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
				Route::get('{meetingId}/followup/{taskid}', 'Meetings\TaskController@viewFollowup')->where('minueId', '[0-9]+')->where('taskid', '[0-9]+');
				Route::get('view/{minuteId}', 'Meetings\MinuteController@viewMinute')->where('minueId', '[0-9]+');
				Route::get('{minuteId}/acceptTask/{taskid}', 'Meetings\TaskController@acceptTask')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
				Route::post('{minuteId}/rejectTask/{taskid}', 'Meetings\TaskController@rejectTask')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
				Route::post('{minuteId}/task/{taskid}/taskComment', 'Meetings\TaskController@taskComment')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
				Route::post('{minuteId}/task/{taskid}/followupComment', 'Meetings\TaskController@followupComment')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
				Route::get('{minuteId}/task/{taskid}', 'Meetings\TaskController@viewTask')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
				Route::get('{minuteId}/markComplete/{taskid}', 'Meetings\TaskController@markComplete')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
				Route::get('{minuteId}/acceptCompletion/{taskid}', 'Meetings\TaskController@acceptCompletion')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
				Route::get('{minuteId}/rejectCompletion/{taskid}', 'Meetings\TaskController@rejectCompletion')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
				Route::post('{meetingId}', 'Meetings\MinuteController@create')->where('meetingId', '[0-9]+');
				Route::post('{minuteId}/update', 'Meetings\MinuteController@update')->where('minueId', '[0-9]+');
				Route::post('{minuteId}/draft', 'Meetings\MinuteController@draft')->where('minueId', '[0-9]+');
				Route::post('{minuteId}/task', 'Meetings\TaskController@createTask')->where('minueId', '[0-9]+');
			});
		
		//});
		
	});	