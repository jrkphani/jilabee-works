<?php namespace App\Http\Controllers\Meetings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\JobTasks;
use App\Model\TempMeetings;
use App\Model\Meetings;
use App\Model\Profile;
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
		return view('meetings.index');
	}
	public function myminutes()
	{
		$tempMeetings = TempMeetings::where('created_by','=',Auth::id())->get();
		$mytask = JobTasks::where('assignee','=',Auth::id())
					->where('status','!=','close')->get();
		return view('meetings.myminutes',['mytask'=>$mytask,'tempMeetings'=>$tempMeetings]);
	}
	public function history()
	{
		$history = JobTasks::where('assignee','=',Auth::id())
					->orWhere('assigner','=',Auth::id())->get();
		return view('jobs.history',['history'=>$history]);
	}
	public function createMeeting(Request $request)
	{
		$input = $request->all();
		$output['success'] = 'yes';
		$validator = TempMeetings::validation($input);
		if ($validator->fails())
		{
			$output['success'] = 'no';
			$output['validator'] = $validator->messages()->toArray();
			return json_encode($output);
		}
		else
		{
			$input['created_by'] = $input['updated_by'] = Auth::id();
			$input['minuters'] = implode(',',$input['minuters']);
			$input['attendees'] = implode(',',$request->input('attendees',[]));
			if(Auth::user()->isAdmin)
			{
				Meetings::create($input);
			}
			else
			{
				$input['requested_by'] = Profile::where('userId','=',Auth::user()->userId)->first()->name;
				TempMeetings::create($input);
			}
			return json_encode($output);
		}
	}
}
