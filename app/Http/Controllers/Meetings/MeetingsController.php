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
		$mymeetings = Meetings::whereRaw('FIND_IN_SET("'.Auth::id().'",attendees)')->orWhereRaw('FIND_IN_SET("'.Auth::id().'",minuters)')->orderBy('updated_at','desc')->get();
		//$myminutes = Meetings::whereRaw('FIND_IN_SET("'.$userId.'",attendees)')->orWhereRaw('FIND_IN_SET("'.$userId.'",minuters)')->get();
		$mytask = JobTasks::where('assignee','=',Auth::id())
					->where('status','!=','close')->get();
		return view('meetings.myminutes',['mymeetings'=>$mymeetings,'tempMeetings'=>$tempMeetings]);
	}
	public function history()
	{
		$meetings = Meetings::whereRaw('FIND_IN_SET("'.Auth::id().'",attendees)')->orWhereRaw('FIND_IN_SET("'.Auth::id().'",minuters)')->orderBy('updated_at','desc')->get();
		return view('meetings.history',['meetings'=>$meetings]);
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
				$input['requested_by'] = Profile::where('userId','=',Auth::id())->first()->name;
				TempMeetings::create($input);
			}
			return json_encode($output);
		}
	}
	public function loadMeeting($mid)
	{
		$tempMeetings = TempMeetings::where('id','=',$mid)
				->where('created_by','=',Auth::id())->first();
		if($tempMeetings)
		{
			return view('meetings.tempMeeting',['tempMeetings'=>$tempMeetings]);
		}
		else
		{
			abort('404');
		}	
	}
	public function updateMeeting(Request $request)
	{
		$input = $request->all();
		$mid = $input['mid'];
		unset($input['mid']);
		unset($input['_token']);
		unset($input['selectMinuters']);
		unset($input['selectAttendees']);
		$validator = TempMeetings::validation($input);
		if ($validator->fails())
		{
			$tempMeetings = TempMeetings::where('id','=',$mid)
				->where('created_by','=',Auth::id())->first();
			return view('meetings.tempMeeting',['tempMeetings'=>$tempMeetings])->withErrors($validator);
		}
		else
		{
			$input['status'] = 'waiting';
			$input['updated_by'] = Auth::id();
			$input['minuters'] = implode(',',$input['minuters']);
			$input['attendees'] = implode(',',$request->input('attendees',[]));
			//$input['requested_by'] = Profile::where('userId','=',Auth::user()->userId)->first()->name;
			TempMeetings::where('id','=',$mid)->update($input);
			return 'success';
		}
	}
}
