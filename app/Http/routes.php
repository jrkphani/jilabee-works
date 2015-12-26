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
/*Route::get('/testemail', function(){
//https://www.digitalocean.com/community/tutorials/how-to-install-and-setup-postfix-on-ubuntu-12-04
//sendEmail($toEmail,$toName,$subject,$view,$arrayToView)
sendEmail('manimani1014@gmail.com','Mani','Ha Ha Ha','emails.password',['token'=>'ssvsdvsdvsvs']);
});*/
Route::group(['middleware' => 'logAllActivity'], function()
{
	Route::group(['prefix' => 'admin'], function()
	{
		Route::group(['middleware' => 'adminOnly'], function()
		{
				Route::get('/', ['uses'=>'Admin\MeetingsController@notification','as'=>'admin']);
				Route::get('user/list', ['uses'=>'Admin\UserController@userList','as'=>'users']);
				Route::get('user/add', 'Admin\UserController@getAdd');
				Route::post('user/add', 'Admin\UserController@postAdd');
				Route::get('user/view/{userId}', 'Admin\UserController@getUser');
				Route::get('user/edit/{userId}', 'Admin\UserController@getAdd');
				Route::post('user/edit/{userId}', 'Admin\UserController@editUser');
				// Route::get('meetings', ['uses'=>'Admin\MeetingsController@index','as'=>'adminmeetings']);
				// Route::get('meeting/create','Admin\MeetingsController@meetingForm');
				// Route::post('meeting/create','Admin\MeetingsController@createMeeting');
				// Route::get('meeting/view/{meetingId}', 'Admin\MeetingsController@view')->where('meetingId', '[0-9]+');
				// Route::get('meeting/draft/{meetingId}', 'Admin\MeetingsController@viewTemp')->where('meetingId', '[0-9]+');
				// Route::get('meeting/newusers/{meetingId}', 'Admin\MeetingsController@viewNewusers')->where('meetingId', '[0-9]+');
				// Route::post('meeting/newusers/{meetingId}', 'Admin\MeetingsController@addUsers')->where('meetingId', '[0-9]+');
				// Route::get('meeting/approve/{meetingId}', 'Admin\MeetingsController@approve')->where('meetingId', '[0-9]+');
				// Route::post('meeting/disapprove/{meetingId}', 'Admin\MeetingsController@disapprove')->where('meetingId', '[0-9]+');
				// Route::get('meeting/edit/{meetingId}','Admin\MeetingsController@meetingForm')->where('meetingId', '[0-9]+');
				// Route::get('meeting/activate/{meetingId}','Admin\MeetingsController@activate')->where('meetingId', '[0-9]+');
				// Route::get('meeting/delete/{meetingId}','Admin\MeetingsController@delete')->where('meetingId', '[0-9]+');
		});
		// Route::get('auth/register', 'Admin\AuthController@signupGet');
		// Route::post('auth/register', 'Admin\AuthController@signupPost');
		Route::get('auth/login', 'Admin\AuthController@loginGet');
		Route::post('auth/login', 'Admin\AuthController@loginPost');
		Route::get('auth/logout', 'Admin\AuthController@logout');
		Route::get('activate/{remember}', 'Admin\UserController@emailActivate');
	});

	Route::controllers([
		'auth' => 'Auth\AuthController',
		'password' => 'Auth\PasswordController',
		]);
	
	//ticket form
	Route::get('ticket/new', ['uses'=>'Followups\TaskController@newticket']);
	Route::post('ticket/new', ['uses'=>'Followups\TaskController@newticketpost']);
	Route::get('ticket/view/{id?}', ['uses'=>'Followups\TaskController@viewTicket']);

	Route::group(['middleware' => 'auth'], function()
	{
		Route::get('profile/{id?}', ['uses'=>'Auth\ProfileController@index','as'=>'profile'])->where('id', '[0-9]+');
		Route::get('profile/edit', 'Auth\ProfileController@getedit');
		Route::post('profile/edit', 'Auth\ProfileController@postedit');

		Route::get('/', ['uses'=>'Jobs\TaskController@index','as'=>'/']);
		Route::get('user/search', 'Auth\ProfileController@findUser');
		Route::get('assigner/search', 'Auth\ProfileController@findAssigner');
		Route::get('assignee/search', 'Auth\ProfileController@findAssignee');
		//Route::get('meeting/search', 'Meetings\MeetingsController@findMeeting');
		Route::get('notifications', 'Auth\ProfileController@notifications');
		Route::get('notifications/all', 'Auth\ProfileController@allNotifications');

		Route::group(['prefix' => 'jobs'], function()
		{
			Route::get('/', ['uses'=>'Jobs\TaskController@index','as'=>'jobs']);
			Route::get('/readNotification', ['uses'=>'Jobs\TaskController@isReadNotification']);
			Route::get('/now', ['uses'=>'Jobs\TaskController@nowsortby']);
			Route::get('/history', ['uses'=>'Jobs\TaskController@historysortby']);
			Route::get('acceptTask/{taskid}', 'Jobs\TaskController@acceptTask')->where('taskid', '[0-9]+');
			Route::post('rejectTask/{taskid}', 'Jobs\TaskController@rejectTask')->where('taskid', '[0-9]+');
			Route::get('cancelTask/{taskid}', 'Jobs\TaskController@cancelTask')->where('taskid', '[0-9]+');
			Route::get('deleteTask/{taskid}', 'Jobs\TaskController@deleteTask')->where('taskid', '[0-9]+');
			Route::post('comment/{taskid}', 'Jobs\TaskController@taskComment')->where('taskid', '[0-9]+');
			Route::post('task/update/{taskid}', 'Jobs\TaskController@updateTask')->where('taskid', '[0-9]+');
			Route::get('task/edit/{taskid}', 'Jobs\TaskController@taskForm')->where('taskid', '[0-9]+');
			Route::get('task/{taskid}', 'Jobs\TaskController@viewTask')->where('taskid', '[0-9]+');
			Route::get('markComplete/{taskid}', 'Jobs\TaskController@markComplete')->where('taskid', '[0-9]+');
			Route::get('acceptCompletion/{taskid}', 'Jobs\TaskController@acceptCompletion')->where('taskid', '[0-9]+');
			Route::get('rejectCompletion/{taskid}', 'Jobs\TaskController@rejectCompletion')->where('taskid', '[0-9]+');
			Route::get('history/{taskid}', 'Jobs\TaskController@viewHistory')->where('taskid', '[0-9]+');
			Route::post('createTask','Jobs\TaskController@createTask');
		});
		Route::group(['prefix' => 'followups'], function()
		{
			Route::get('/', ['uses'=>'Followups\TaskController@index','as'=>'followups']);
			Route::get('/readNotification', ['uses'=>'Followups\TaskController@isReadNotification']);
			Route::get('/now', ['uses'=>'Followups\TaskController@nowsortby']);
			Route::get('/history', ['uses'=>'Followups\TaskController@historysortby']);
			Route::post('draft', 'Followups\TaskController@draft');
			Route::get('deleteDraft/{taskid}', 'Followups\TaskController@deleteDraft')->where('taskid', '[0-9]+');
			Route::get('draftform/{taskid?}', 'Followups\TaskController@draftform')->where('taskid', '[0-9]+');
			Route::get('task/{taskid}', 'Followups\TaskController@viewTask')->where('taskid', '[0-9]+');
			Route::get('{minuteId}/task/{taskid}', 'Followups\TaskController@viewMinute')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
			Route::post('comment/{taskid}', 'Followups\TaskController@taskComment')->where('taskid', '[0-9]+');
			Route::post('{minuteId}/comment/{taskid}', 'Followups\TaskController@minuteComment')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
		});
		// Route::group(['prefix' => 'meetings'], function()
		// {
		// 	Route::get('/', ['uses'=>'Meetings\MeetingsController@index','as'=>'meetings']);
		// 	Route::get('/readNotification', ['uses'=>'Meetings\MeetingsController@isReadNotification']);
		// 	Route::get('now', ['uses'=>'Meetings\MeetingsController@nowsortby']);
		// 	Route::get('history', ['uses'=>'Meetings\MeetingsController@historysortby']);
		// 	Route::get('create','Meetings\MeetingsController@meetingForm');
		// 	Route::post('create','Meetings\MeetingsController@createMeeting');
		// 	Route::post('draft','Meetings\MeetingsController@draftMeeting');
		// 	Route::get('load/{temMeetingId}','Meetings\MeetingsController@meetingForm')->where('id', '[0-9]+');
		// });
		// Route::group(['prefix' => 'minute'], function()
		// {
		// 	Route::get('{minuteId}', 'Meetings\MinuteController@index')->where('minuteId', '[0-9]+');
		// 	Route::get('first/{meetingId}', 'Meetings\MinuteController@startMinute')->where('meetingId', '[0-9]+');
		// 	Route::get('view/{minuteId}', 'Meetings\MinuteController@viewMinute')->where('minuteId', '[0-9]+');
		// 	Route::get('{minuteId}/acceptTask/{taskid}', 'Meetings\TaskController@acceptTask')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
		// 	Route::post('{minuteId}/rejectTask/{taskid}', 'Meetings\TaskController@rejectTask')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
		// 	Route::post('{minuteId}/comment/{taskid}', 'Meetings\TaskController@taskComment')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
		// 	Route::get('{minuteId}/task/{taskid}', 'Meetings\TaskController@viewTask')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
		// 	Route::get('{minuteId}/markComplete/{taskid}', 'Meetings\TaskController@markComplete')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
		// 	Route::get('{minuteId}/acceptCompletion/{taskid}', 'Meetings\TaskController@acceptCompletion')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
		// 	Route::get('{minuteId}/rejectCompletion/{taskid}', 'Meetings\TaskController@rejectCompletion')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
		// 	Route::post('{minuteId}/draft', 'Meetings\MinuteController@draft')->where('minuteId', '[0-9]+');
		// 	Route::post('{minuteId}/task', 'Meetings\TaskController@createTask')->where('minuteId', '[0-9]+');
		// 	Route::get('{meetingId}/next', 'Meetings\MinuteController@nextMinute')->where('minuteId', '[0-9]+');
		// 	Route::post('{meetingId}/next', 'Meetings\MinuteController@create')->where('minuteId', '[0-9]+');
		// 	Route::get('{minuteId}/history/{taskid}', 'Meetings\TaskController@viewHistory')->where('minuteId', '[0-9]+')->where('taskid', '[0-9]+');
		// });		
	});	
});