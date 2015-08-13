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

Route::get('/testemail', function(){
	Mail::send(
  'emails.password',
  array( 'token' => 'testing' ),
  function( $message ) {
    $message->from( 'mani.r@mtlabs.in', 'Code Chewing' );
    $message->to(
      'manimani1014@gmail.com',
      'name'
    )->subject( 'Welcome to Code Chewing!' );
  }
);
});
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
			Route::get('user/view/{userId}', 'Admin\UserController@getUser');
			Route::get('meetings', 'Admin\MeetingsController@index');
			Route::get('meeting/view/{meetingId}', 'Admin\MeetingsController@view')->where('meetingId', '[0-9]+');
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
		Route::get('profile/{id?}', 'Auth\ProfileController@index')->where('id', '[0-9]+');
		Route::get('profile/edit', 'Auth\ProfileController@getedit');
		Route::post('profile/edit', 'Auth\ProfileController@postedit');

		Route::get('/', 'Jobs\TaskController@index');
		Route::get('user/search', 'Auth\ProfileController@findUser');

		Route::group(['prefix' => 'jobs'], function()
		{
			Route::get('/', 'Jobs\TaskController@index');
			Route::post('draft', 'Jobs\TaskController@draft');
			Route::get('draftform/{taskid?}', 'Jobs\TaskController@draftform')->where('taskid', '[0-9]+');
			Route::get('acceptTask/{taskid}', 'Jobs\TaskController@acceptTask')->where('taskid', '[0-9]+');
			Route::post('rejectTask/{taskid}', 'Jobs\TaskController@rejectTask')->where('taskid', '[0-9]+');
			Route::post('comment/{taskid}', 'Jobs\TaskController@taskComment')->where('taskid', '[0-9]+');
			//Route::post('task/{taskid}/followupComment', 'Jobs\TaskController@followupComment')->where('taskid', '[0-9]+');
			Route::post('task/update/{taskid}', 'Jobs\TaskController@updateTask')->where('taskid', '[0-9]+');
			Route::get('task/edit/{taskid}', 'Jobs\TaskController@taskForm')->where('taskid', '[0-9]+');
			Route::get('task/{taskid}', 'Jobs\TaskController@viewTask')->where('taskid', '[0-9]+');
			Route::get('markComplete/{taskid}', 'Jobs\TaskController@markComplete')->where('taskid', '[0-9]+');
			Route::get('acceptCompletion/{taskid}', 'Jobs\TaskController@acceptCompletion')->where('taskid', '[0-9]+');
			Route::get('rejectCompletion/{taskid}', 'Jobs\TaskController@rejectCompletion')->where('taskid', '[0-9]+');
			//Route::get('followup/{taskid}', 'Jobs\TaskController@viewFollowup')->where('taskid', '[0-9]+');
			//Route::get('mytask', 'Jobs\TaskController@mytask');
			//Route::get('followups', 'Jobs\TaskController@followups');
			Route::get('history', 'Jobs\TaskController@history');
			Route::get('history/{taskid}', 'Jobs\TaskController@viewHistory')->where('taskid', '[0-9]+');
			Route::post('createTask','Jobs\TaskController@createTask');
		});
		Route::group(['prefix' => 'followups'], function()
		{
			Route::get('/', 'Followups\TaskController@index');
			Route::get('task/{taskid}', 'Followups\TaskController@viewTask')->where('taskid', '[0-9]+');
			Route::get('{minuteId}/task/{taskid}', 'Followups\TaskController@viewMinute')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
			Route::post('comment/{taskid}', 'Followups\TaskController@taskComment')->where('taskid', '[0-9]+');
			Route::post('{minuteId}/comment/{taskid}', 'Followups\TaskController@minuteComment')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
		});
		Route::group(['prefix' => 'meetings'], function()
		{
			Route::get('/', 'Meetings\MeetingsController@index');
			//Route::get('myminutes', 'Meetings\MeetingsController@myminutes');
			//Route::get('history', 'Meetings\MeetingsController@history');
			Route::get('create','Meetings\MeetingsController@meetingForm');
			Route::post('create','Meetings\MeetingsController@createMeeting');
			Route::get('load/{temMeetingId}','Meetings\MeetingsController@loadMeeting')->where('id', '[0-9]+');
			Route::post('update/{temMeetingId}','Meetings\MeetingsController@updateMeeting')->where('id', '[0-9]+');
		});
		Route::group(['prefix' => 'minute'], function()
		{
			Route::get('{minuteId}', 'Meetings\MinuteController@index')->where('minuteId', '[0-9]+');
			//Route::get('{meetingId}/followup/{taskid}', 'Meetings\TaskController@viewFollowup')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
			Route::get('view/{minuteId}', 'Meetings\MinuteController@viewMinute')->where('minuteId', '[0-9]+');
			Route::get('{minuteId}/acceptTask/{taskid}', 'Meetings\TaskController@acceptTask')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
			Route::post('{minuteId}/rejectTask/{taskid}', 'Meetings\TaskController@rejectTask')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
			Route::post('{minuteId}/comment/{taskid}', 'Meetings\TaskController@taskComment')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
			//Route::get('{minuteId}/task/edit/{taskid}', 'Meetings\TaskController@taskForm')->where('taskid', '[0-9]+');
			Route::get('{minuteId}/task/{taskid}', 'Meetings\TaskController@viewTask')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
			Route::get('{minuteId}/markComplete/{taskid}', 'Meetings\TaskController@markComplete')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
			Route::get('{minuteId}/acceptCompletion/{taskid}', 'Meetings\TaskController@acceptCompletion')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
			Route::get('{minuteId}/rejectCompletion/{taskid}', 'Meetings\TaskController@rejectCompletion')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
			//Route::post('{meetingId}', 'Meetings\MinuteController@create')->where('meetingId', '[0-9]+');
			//Route::post('{minuteId}/update', 'Meetings\MinuteController@update')->where('minuteId', '[0-9]+');
			Route::post('{minuteId}/draft', 'Meetings\MinuteController@draft')->where('minuteId', '[0-9]+');
			Route::post('{minuteId}/task', 'Meetings\TaskController@createTask')->where('minuteId', '[0-9]+');
			Route::get('{meetingId}/next', 'Meetings\MinuteController@nextMinute')->where('minuteId', '[0-9]+');
			Route::post('{meetingId}/next', 'Meetings\MinuteController@create')->where('minuteId', '[0-9]+');
			Route::get('{minuteId}/history/{taskid}', 'Meetings\TaskController@viewHistory')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
		});
		
		//});
		
	});	