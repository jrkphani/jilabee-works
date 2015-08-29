<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\TempMeetings;
use App\Model\Meetings;
use App\Model\Organizations;
use Auth;
use App\User;
use Validator;
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
		$meetings = Meetings::select('meetings.*')->join('organizations','meetings.oid','=','organizations.id')
					->where('organizations.customerId','=',getOrgId())
					->where('meetings.approved','=','1')
					->get();
		return view('admin.meetings',['meetings'=>$meetings]);
	}
	public function notification()
	{
		$meetings = Meetings::select('meetings.*')->join('organizations','meetings.oid','=','organizations.id')
					->where('organizations.customerId','=',getOrgId())
					->where('meetings.approved','=','0')
					->get();
		return view('admin.notification',['meetings'=>$meetings]);
	}
	public function view($id)
	{
		$meeting = Meetings::find($id);
		if($meeting->approved == '0')
		{
			//for pop view in meeting approve page
			return view('admin.meetingPop',['meeting'=>$meeting]);
		}
		else
		{
			return view('admin.meeting',['meeting'=>$meeting]);
		}
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
		$roles = roles();
		//return view('meetings.form',['meeting'=>$meeting]);
		return view('admin.meetingForm',['meeting'=>$meeting,'roles'=>$roles]);
	}
	public function createMeeting()
	{
		$input = Request::only('title','description','venue','participants','roles');
		$output['success'] = 'yes';
		if(!Auth::user()->isAdmin)
		{
			$input['minuters'][0] = Auth::user()->userId;
		}
		$minuters=$attendees=$attendeesEmail=array();
		$validator = Meetings::validation($input);
		if ($validator->fails())
		{
			$output['success'] = 'no';
			$output['validator'] = $validator->messages()->toArray();
			return json_encode($output);
		}
		else
		{
			if(count($input['participants']) != count($input['roles']))
			{
				$output['success'] = 'no';
				$validator->errors()->add('participants','Something is wrong with this field!');
				$output['validator'] = $validator->messages()->toArray();
				return json_encode($output);
			}

				foreach ($input['participants'] as $key => $value)
				{
					if($input['roles'][$key] == '1')
					{
						//attendees
						if(isEmail($value))
						{
							if($assignee = getUser(['email'=>$value]))
							{
								$attendees[] = $assignee->id;
							}
							else
							{
								$attendeesEmail[] = $value;
							}
						}
						else
						{
							$attendees[] = $value;
						}
					}
					elseif($input['roles'][$key] == '2')
					{
						$minuters[] = $value;
					}
				}
				if(!count($minuters))
				{
					$output['success'] = 'no';
					$validator->errors()->add('participants','Atleast one minuter is require!');
					$output['validator'] = $validator->messages()->toArray();
					return json_encode($output);
				}
			
			$input['created_by'] = $input['updated_by'] = Auth::id();
			$getMinutersId = User::whereIn('userId',$minuters)->lists('id');
			$input['minuters'] = implode(',',$getMinutersId);
			if(count($attendees))
			{
				$attendees = User::whereIn('userId',$attendees)->lists('id');
			}
			if(count($attendeesEmail))
			{
				$attendees = array_merge($attendees,$attendeesEmail);
			}
			$input['attendees'] = implode(',',$attendees);
			$input['description'] = nl2br($input['description']);
			if($mid = Request::get('id'))
				{
					$meeting = Meetings::whereId($mid)->first();
					if(Auth::user()->isAdmin)
					{
						$input['approved'] = 1;
					}
					$meeting->update($input);
				}
				else
				{
					if(Auth::user()->isAdmin)
					{
						$input['approved'] = 1;
					}
					$input['oid']= Organizations::where('customerId','=',getOrgId())->first()->id;
					$input['requested_by'] = Auth::id();
					$meeting = Meetings::create($input);
				}
				$output['meetingId'] = $meeting->id;
			return json_encode($output);
		}
	}
	// public function approve(Request $request)
	// {
	// 	$input = $request->all();
	// 	if($tempMeetings = TempMeetings::find($input['mid']))
	// 	{
	// 		$output['success'] = 'yes';
	// 		$validator = TempMeetings::validation($input);
	// 		if ($validator->fails())
	// 		{
	// 			$output['success'] = 'no';
	// 			$output['validator'] = $validator->messages()->toArray();
	// 			return json_encode($output);
	// 		}
	// 		else
	// 		{
	// 			$input['created_by'] = $tempMeetings->created_by;
	// 			$input['updated_by'] = Auth::id();
	// 			$input['minuters'] = implode(',',$input['minuters']);
	// 			$input['attendees'] = implode(',',$request->input('attendees',[]));
	// 			if(Meetings::create($input))
	// 			{
	// 				$tempMeetings->delete();
	// 				return json_encode($output);
	// 			}
	// 		}
	// 	}
	// 	else
	// 	{
	// 		return abort('403');
	// 	}
	// }
	// public function disapprove(Request $request)
	// {
	// 	$input = $request->all();
	// 	$tempMeetings = TempMeetings::find($input['mid'])->first();
	// 	if($tempMeetings)
	// 	{
	// 		if(!$input['reason'])
	// 		{
	// 			$output['success'] = 'no';
	// 			$output['reason'] = 'Reason for reject is require.';
	// 			return json_encode($output);
	// 		}
	// 		$output['success'] = 'yes';
	// 		$tempMeetings->status = 'Rejected';
	// 		$tempMeetings->reason = $input['reason'];
	// 		$tempMeetings->updated_by = Auth::id();
	// 		$tempMeetings->save();
	// 		return json_encode($output);
	// 	}
	// 	else
	// 	{
	// 		return abort('403');
	// 	}
	// }
}
