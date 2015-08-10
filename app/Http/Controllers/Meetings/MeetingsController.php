<?php namespace App\Http\Controllers\Meetings;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\TempMeetings;
use App\Model\Meetings;
use App\Model\Minutes;
use App\Model\Profile;
use App\User;
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
		$minutes = Minutes::whereRaw('FIND_IN_SET("'.Auth::id().'",attendees)')->orderBy('minuteDate','desc')->get();
		return view('meetings.index',['minutes'=>$minutes]);
	}
	public function meetingForm()
	{
		return view('meetings.form');
	}
	public function createMeeting()
	{
		$input = Request::only('title','description','venue','attendees','minuters');
		$output['success'] = 'yes';
		$attendeesEmail=array();
		$validator = TempMeetings::validation($input);
		if ($validator->fails())
		{
			$output['success'] = 'no';
			$output['validator'] = $validator->messages()->toArray();
			return json_encode($output);
		}
		else
		{
			if($input['attendees'])
			{
				foreach ($input['attendees'] as $key => $value)
				{
					if(isEmail($value))
					{
						$attendeesEmail[] = $value;
					}
					else
					{
						$attendees[] = $value;
					}
				}
			}
			$input['created_by'] = $input['updated_by'] = Auth::id();
			$getMinutersId = User::whereIn('userId',$input['minuters'])->lists('id');
			$input['minuters'] = implode(',',$getMinutersId);
			if(count($attendees))
			{
				$getAttendeesId = User::whereIn('userId',$attendees)->lists('id');
			}
			if(count($attendeesEmail))
			{
				if($getAttendeesId)
				{
					$attendees = array_merge($getAttendeesId,$attendeesEmail);
				}
				else
				{
					$attendees = $attendeesEmail;
				}
			}
			$input['attendees'] = implode(',',$attendees);
			$input['description'] = nl2br($input['description']);
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
	public function updateMeeting()
	{
		$input = Request::all();
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
			$input['status'] = 'Sent';
			$input['updated_by'] = Auth::id();
			$input['minuters'] = implode(',',$input['minuters']);
			$input['attendees'] = implode(',',$input['attendees']);
			//$input['requested_by'] = Profile::where('userId','=',Auth::user()->userId)->first()->name;
			TempMeetings::where('id','=',$mid)->update($input);
			return 'success';
		}
	}
}
