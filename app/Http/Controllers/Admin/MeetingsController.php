<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\JobTasks;
use App\Model\TempMeetings;
use App\Model\Meetings;
use Auth;
class MeetingsController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	/*public function __construct()
	{
	}*/
	public function index()
	{
		$meetings = TempMeetings::all();
		return view('admin.meetings',['meetings'=>$meetings]);
	}
}
