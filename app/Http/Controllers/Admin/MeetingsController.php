<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Request;
use App\Model\TempMeetings;
use App\Model\MinuteTasks;
use App\Model\Minutes;
use App\Model\Ideas;
use App\Model\Meetings;
use App\Model\Organizations;
use App\Model\Notifications;
use Auth;
use Session;
use DB;
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
					//enable this on filter 
					//->where('meetings.active','=','1')
					->withTrashed()
					->get();
		return view('admin.meetings',['meetings'=>$meetings]);
	}
	public function notification()
	{
		$meetings = TempMeetings::select('tempMeetings.*')->join('organizations','tempMeetings.oid','=','organizations.id')
					->where('organizations.customerId','=',getOrgId())
					//->where('tempMeetings.approved','=','0')
					->where('tempMeetings.draft','=','0')
					->get();
		$notifications = Auth::user()->notifications()->where('objectType','meetinguser')->where('isRead','0')->orderBy('updated_at','desc')->get();
		//print_r($notifications); die;
		return view('admin.notification',['meetings'=>$meetings,'notifications'=>$notifications]);
	}
	public function view($mid)
	{	$meeting = Meetings::whereId($mid)->withTrashed()->first();
		$roles = roles();
		return view('admin.meeting',['meeting'=>$meeting,'roles'=>$roles]);
	}
	public function viewTemp($id)
	{
		$meeting = TempMeetings::find($id);
		$roles = roles();
		//for pop view in meeting approve page
		return view('admin.meetingPop',['meeting'=>$meeting,'roles'=>$roles]);
	}
	public function viewNewusers($id)
	{
		$meeting = Meetings::find($id);
		$roles = roles();
		$notification = Notifications::where('objectType','meetinguser')->where('objectId',$id)->where('userId',Auth::id())->first();
		//for pop view in meeting approve page
		return view('admin.meetingNewusers',['meeting'=>$meeting,'roles'=>$roles,'notification'=>$notification]);
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
		$input = Request::only('title','description','venue','participants','roles','type','purpose');
		$output['success'] = 'yes';
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
								$attendees[] = $assignee->userId;
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
					$meeting->update($input);
					$notification['userId'] = $meeting->minuters;
					$notification['objectId'] = $meeting->id;
					$notification['objectType'] = 'meeting';
					$notification['subject'] ='new';
					$notification['body'] = 'Modificatoins have been made to '.$meeting->title.' meeting';
					setNotification($notification);
					foreach (explode(',', $meeting->attendees)as $user)
					{
						if(!isEmail($user))
						{
							$notification['userId'] = $user;
							$notification['body'] = 'Modificatoins have been made to'.$meeting->title.' meeting';
							setNotification($notification);
						}
					}
					Session::flash('message', 'Meeting updated');
				}
				else
				{
					$input['oid']= Organizations::where('customerId','=',getOrgId())->first()->id;
					$input['requested_by'] = Auth::id();
					$meeting = Meetings::create($input);
					$notification['userId'] = $meeting->minuters;
					$notification['objectId'] = $meeting->id;
					$notification['objectType'] = 'meeting';
					$notification['subject'] ='new';
					$notification['body'] = 'You are added as minuter to '.$meeting->title.' meeting';
					setNotification($notification);
					foreach (explode(',', $meeting->attendees)as $user)
					{
						if(!isEmail($user))
						{
							$notification['userId'] = $user;
							$notification['body'] = 'You are added as participant to '.$meeting->title.' meeting';
							setNotification($notification);
						}
					}
					Session::flash('message', 'Meeting created');
				}
				$output['meetingId'] = $meeting->id;
			return json_encode($output);
		}
	}
	public function approve($mid)
	{
		if($meeting = TempMeetings::find($mid))
		{
			$output['success'] = 'yes';
			$meetingData['title'] = $meeting->title;
			$meetingData['description'] = $meeting->description;
			$meetingData['venue'] = $meeting->venue;
			$meetingData['minuters'] = $meeting->minuters;
			$meetingData['oid'] = $meeting->oid;
			$meetingData['type'] = $meeting->type;
			$meetingData['purpose'] = $meeting->purpose;

			$details = unserialize($meeting->details);
			$attendees = $records = $ideasArr =array();
			for($i=0;$i<=count($details['title'])-1;$i++)
			{
				$tempArr['title'] = $details['title'][$i];
				$tempArr['description'] = nl2br($details['description'][$i]);
				if($details['type'][$i] == 'task')
				{
					$tempArr['assigner'] = Auth::id();
					$tempArr['dueDate'] = $details['dueDate'][$i];
					$tempArr['updated_by'] = Auth::id();
					$tempArr['status'] = 'Sent';

					if(isEmail($details['assignee'][$i]))
					{
						if($assignee = getUser(['email'=>$details['assignee'][$i]]))
						{
							//check user has an account
							$details['assignee'][$i] = $assignee->id;
						}
						else
						{
							//mark the task as accepted for who do have an account
							$tempArr['status'] = 'Open';
						}

					}
					else if($assignee = getUser(['userId'=>$details['assignee'][$i]]))
					{
						$details['assignee'][$i] = $assignee->id;
					}
					$attendees[] = $details['assignee'][$i];
					$tempArr['assignee'] = $details['assignee'][$i];
					$tempArr['updated_by'] = $meeting->created_by;
					$tempArr['created_by'] = $meeting->created_by;
					$records[] = New MinuteTasks(array_filter($tempArr));
				}
				elseif($details['type'][$i] == 'idea')
				{
					if(isEmail($details['orginator'][$i]))
					{
						if($orginator = getUser(['email'=>$details['orginator'][$i]]))
						{
							//check user has an account
							$details['orginator'][$i] = $orginator->id;
						}

					}
					else if($orginator = getUser(['userId'=>$details['orginator'][$i]]))
					{
						$details['orginator'][$i] = $orginator->id;
					}
					$attendees[] = $details['orginator'][$i];
					$tempArr['orginator'] = $details['orginator'][$i];
					$tempArr['updated_by'] = $meeting->created_by;
					$tempArr['created_by'] = $meeting->created_by;
					$ideasArr[] = New Ideas(array_filter($tempArr));
				}
			}					



			$meetingData['attendees'] =implode(',', $attendees);
			$meetingData['requested_by'] =$meeting->created_by;
			$meetingData['created_by'] = $meetingData['updated_by'] =Auth::id();
			DB::transaction(function() use ($meetingData,$records,$ideasArr,$meeting,$attendees)
			{
				if($newmeeting = Meetings::create($meetingData))
				{
					$attendees[] = $meeting->minuters;
					$minute = New Minutes(['startDate'=>$meeting->startDate,
						'created_by'=>$meeting->created_by,'updated_by'=>$meeting->created_by,
						'endDate'=>$meeting->endDate,'attendees'=>implode(',',$attendees)]);
					$minute = $newmeeting->minutes()->save($minute);
					$minute->tasks()->saveMany($records);
					$minute->ideas()->saveMany($ideasArr);
					$meeting->delete();
					Session::flash('message', 'Meeting request accepted');
				}
			});			
			return json_encode($output);
		}
		else
		{
			return abort('403');
		}
	}
	public function disapprove($mid)
	{
		$input = Request::only('reason');
		$meeting = TempMeetings::whereId($mid)->first();
		if($meeting)
		{
			if(!$input['reason'])
			{
				$output['success'] = 'no';
				$output['reason'] = 'Reason for reject is require.';
				return json_encode($output);
			}
			$output['success'] = 'yes';
			$meeting->approved = '-1';
			$meeting->reason = $input['reason'];
			$meeting->updated_by = Auth::id();
			$meeting->save();
			$user = getUser(['id'=>$meeting->updated_by]);
			sendEmail($user->email,$user->profile->name,'Meeting Rejected','emails.meetingReject',['meeting'=>$meeting]);
			Session::flash('message', 'Meeting request rejected');
			return json_encode($output);
		}
		else
		{
			return abort('403');
		}
	}
	public function activate($mid)
	{
		$meeting = Meetings::whereId($mid)->first();
		if($meeting)
		{
			$output['success'] = 'yes';
			if($meeting->active == '1')
			{
				$meeting->active = '0';
			}
			else
			{
				$meeting->active = '1';
			}
			$meeting->updated_by = Auth::id();
			$meeting->save();
			$output['active'] = $meeting->active;
			return json_encode($output);
		}
		else
		{
			return abort('403');
		}
	}
	public function delete($mid)
	{
		$meeting = Meetings::whereId($mid)->first();
		if($meeting)
		{
			$output['success'] = 'yes';
			$meeting->delete();
			$notification['userId'] = $meeting->minuters;
			$notification['objectId'] = $mid;
			$notification['objectType'] = 'meeting';
			$notification['subject'] ='end';
			$notification['tag'] ='history';
			$notification['body'] = 'Meeting '.$meeting->title.' has been ended';
			setNotification($notification);
			$getuser = getUser(['id'=>$meeting->minuters]);
			sendEmail($getuser->email,$getuser->profile->name,'Meeting Ended','emails.meetingEnd',['meeting'=>$meeting]);
			foreach (explode(',', $meeting->attendees)as $user)
			{
				if(!isEmail($user))
				{
					$notification['userId'] = $user;
					setNotification($notification);
					$getuser = getUser(['id'=>$user]);
					sendEmail($getuser->email,$getuser->profile->name,'Meeting Ended','emails.meetingEnd',['meeting'=>$meeting]);
				}
			}
			
			Session::flash('message', 'Meeting ended');
			return json_encode($output);
		}
		else
		{
			return abort('403');
		}
	}
	public function addUsers($mid)
	{
		$input = Request::only('participants','roles');
		$output['success'] = 'yes';
		$meeting = Meetings::whereId($mid)->first();
		$minuters=$attendees=$attendeesEmail=array();
		if(count($input['participants']))
		{
			if(count($input['participants']) != count($input['roles']))
			{
				$output['success'] = 'no';
				$output['error'] = 'Something is wrong with this field!';
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
		}

		$input['updated_by'] = Auth::id();
		$getMinutersId = User::whereIn('userId',$minuters)->lists('id');
		$input['minuters'] = implode(',',array_merge($getMinutersId,explode(',',$meeting->minuters)));
		if(count($attendees))
		{
			$attendees = User::whereIn('userId',$attendees)->lists('id');
		}
		if(count($attendeesEmail))
		{
			$attendees = array_merge($attendees,$attendeesEmail);
		}

		$input['attendees'] = implode(',',array_merge($attendees,explode(',',$meeting->attendees)));
		if($meeting->update($input))
		{
			//update notify 
			$notification['userId'] = Auth::id();
			$notification['objectId'] = $mid;
			$notification['objectType'] = 'meetinguser';
			readNotification($notification);
			$notification = array();
			$notification['userId'] = $meeting->minuters;
			$notification['objectId'] = $mid;
			$notification['objectType'] = 'meeting';
			$notification['subject'] ='user';
			$notification['body'] = 'Meeting '.$meeting->title.' participants updated';
			setNotification($notification);
			Session::flash('message', 'Meeting participants updated');
		}
		$output['meetingId'] = $mid;
		return json_encode($output);
	}
}
