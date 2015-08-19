<?php namespace App\Http\Controllers\Meetings;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\TempMeetings;
use App\Model\Meetings;
use App\Model\Minutes;
use App\Model\Organizations;
use App\Model\Profile;
use App\User;
use Auth;
use DB;
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
		$newmeetings = Meetings::select('meetings.*')
						->join('organizations','meetings.oid','=','organizations.id')
						//->join('minutes','meetings.id','=','minutes.meetingId')
						->where('organizations.customerId','=',getOrgId())
						->whereRaw('FIND_IN_SET("'.Auth::id().'",meetings.minuters)')
						 ->whereNotExists(function($query)
						 	{
						 	$query->select(DB::raw(1))
		                    		->from('minutes')
		                       		->whereRaw('meetings.id = minutes.meetingId');
						 		})
						->get();
						//print_r($newmeetings); die;
		$minutes = Minutes::whereRaw('FIND_IN_SET("'.Auth::id().'",attendees)')->orderBy('startDate','desc')->get();
		return view('meetings.index',['minutes'=>$minutes,'newmeetings'=>$newmeetings]);
	}
	public function meetingForm($mid=NULL)
	{
		if($mid)
		{
			$meeting = Meetings::find($mid);
		}
		else
		{
			$meeting = NULL;
		}
		return view('meetings.form',['meeting'=>$meeting]);
		//return view('admin.meetingForm',['meeting'=>$meeting]);
	}
	public function createMeeting()
	{
		$input = Request::only('title','description','venue','attendees','minuters');
		$output['success'] = 'yes';
		$attendeesEmail=$attendees=array();
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
				$attendees = User::whereIn('userId',$attendees)->lists('id');
			}
			if(count($attendeesEmail))
			{
				if($attendees)
				{
					$attendees = array_merge($attendees,$attendeesEmail);
				}
				else
				{
					$attendees = $attendeesEmail;
				}
			}
			$input['attendees'] = implode(',',$attendees);
			$input['description'] = nl2br($input['description']);
			if($mid = Request::get('id'))
				{
					$meeting = Meetings::whereId($mid)->first();
					if(Auth::user()->isAdmin)
					{
						$meeting->active = 1;
					}
					$meeting->update($input);
				}
				else
				{
					if(Auth::user()->isAdmin)
					{
						$input['active'] = 1;
					}
					$input['oid']= Organizations::where('customerId','=',getOrgId())->first()->id;
					$input['requested_by'] = Auth::id();
					$meeting = Meetings::create($input);
				}
				$output['meetingId'] = $meeting->id;
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
	// public function updateMeeting()
	// {
	// 	$input = Request::all();
	// 	$mid = $input['mid'];
	// 	unset($input['mid']);
	// 	unset($input['_token']);
	// 	unset($input['selectMinuters']);
	// 	unset($input['selectAttendees']);
	// 	$validator = TempMeetings::validation($input);
	// 	if ($validator->fails())
	// 	{
	// 		$tempMeetings = TempMeetings::where('id','=',$mid)
	// 			->where('created_by','=',Auth::id())->first();
	// 		return view('meetings.tempMeeting',['tempMeetings'=>$tempMeetings])->withErrors($validator);
	// 	}
	// 	else
	// 	{
	// 		$input['status'] = 'Sent';
	// 		$input['updated_by'] = Auth::id();
	// 		$input['minuters'] = implode(',',$input['minuters']);
	// 		$input['attendees'] = implode(',',$input['attendees']);
	// 		//$input['requested_by'] = Profile::where('userId','=',Auth::user()->userId)->first()->name;
	// 		TempMeetings::where('id','=',$mid)->update($input);
	// 		return 'success';
	// 	}
	// }
}
