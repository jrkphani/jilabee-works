<?php namespace App\Http\Controllers\Meetings;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\Meetings;
use App\Model\MinuteTasks;
use App\Model\Minutes;
use App\Model\Profile;
use App\Model\MinuteDraft;
use Auth;
class MinuteController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	/*public function __construct()
	{
	}*/
	public function index($minuteId)
	{
		$minute = Minutes::whereId($minuteId)->whereRaw('FIND_IN_SET("'.Auth::id().'",attendees)')->first();
		if($minute)
		{
			return view('meetings.minute',['minute'=>$minute]);	
		}
	}
	/*public function index($meetingId,$minuteId=NULL)
	{
		//need to secure the link by check the user has permission and particpated in the meeting
		//echo  $meetingId; echo $minuteID; die;
		$meeting = Meetings::find($meetingId);
		$tasks = NULL;
		$ideas = NULL;
		$minute=NULL;
		if($minuteId)
		{
			$minute = Minutes::where('meetingId','=',$meetingId)->where('id','=',$minuteId)->first();
			//$tasks = $minute->tasks()->where('status','!=','Closed')->get();
			//$ideas = $minute->ideas()->get();
		}
		return view('meetings.minute',['meeting'=>$meeting,'minute'=>$minute]);
	}*/
	public function nextMinute($meetingId)
	{
		if($meeting = Meetings::find($meetingId)->isMinuter())
		{
			$minute = $meeting->minutes()->whereFiled('0')->first();
			$participants =array();
			if($minute)
			{
				if($minute->created_by != Auth::id())
				{
					//abort('403');
					echo "Another user is taking minutes.";
					return;
				}
				$attendeesEmail=array();
				if($minute->attendees)
				{
					foreach(explode(',',$minute->attendees) as $value)
					{
						if(isEmail($value))
						{
							$attendeesEmail[$value] = $value;
						}
						else
						{
							$participants[]=$value;
						}
					}
				}
				//echo "here"; die;
			}
			else
			{
				$minute = NULL;
				$attendees = $attendeesEmail=array();
				if($meeting->attendees)
				{
					foreach(explode(',',$meeting->attendees) as $value)
					{
						if(isEmail($value))
						{
							$attendeesEmail[$value] = $value;
						}
						else
						{
							$attendees[]=$value;
						}
					}
					$participants = array_merge(explode(',',$meeting->minuters),$attendees);
				}
				else
				{
					$participants = explode(',',$meeting->minuters);
				}
			}
			//print_r($attendeesEmail); die;
			$users = Profile::select('profiles.name','users.userId')->whereIn('profiles.userId',$participants)
						->join('users','profiles.userId','=','users.id')
			->lists('profiles.name','users.userId');
			return view('meetings.createMinute',['meeting'=>$meeting,'attendees'=>$users,'attendeesEmail'=>$attendeesEmail,'minute'=>$minute]);
		}
	}
	public function create($mid)
	{
		if($meeting = Meetings::find($mid)->isMinuter())
		{
			$input = Request::only('venue','startDate','endDate','attendees','absentees');
			//print_r($input); die;
			if(!$input['endDate'])
			{
				unset($input['endDate']);
			}
			$validator = Minutes::validation($input);
			if ($validator->fails())
			{
				return redirect('minute/'.$mid.'/next')->withErrors($validator);
			}
			//find previous end date of meeting if any
			if($lastFiledMinute = Minutes::where('meetingId','=',$meeting->id)->where('filed','=','1')->orderBy('startDate', 'DESC')->limit(1)->first())
			{
				if($lastFiledMinute)
				{
					if($lastFiledMinute->endDate >= $input['startDate'])
					{
						$validator->errors()->add('startDate','Start date should be greater then last meeting date : '.$lastFiledMinute->endDate);
						return redirect('minute/'.$mid.'/next')->withErrors($validator);
					}
				}
			}
			$emails=$attendees=$newusers=array();
			foreach ($input['attendees'] as $value)
			 	{
			 		if(isEmail($value))
					{
						if($assignee = getUser(['email'=>$value]))
						{
							//check user has an account
							$attendees[] = $assignee->id;
						}
						else
						{
							$emails[]=$value;
						}

					}
					else if($assignee = getUser(['userId'=>$value]))
					{
						$attendees[] = $assignee->id;
					}
			 	}
			 	$attendees = array_merge($attendees,$emails);
			 	$attendeesList =  array_merge(explode(',', $meeting->attendees),explode(',', $meeting->minuters));
				$newusers = array_diff($attendees, $attendeesList);
			 	$emails=$absentees=array();
			 	if($input['absentees'])
			 	{
			 		foreach ($input['absentees'] as $value)
				 	{
				 		if(isEmail($value))
						{
							if($assignee = getUser(['email'=>$value]))
							{
								//check user has an account
								$absentees[] = $assignee->id;
							}
							else
							{
								$emails[]=$value;
							}

						}
						else if($assignee = getUser(['userId'=>$value]))
						{
							$absentees[] = $assignee->id;
						}
				 	}
			 	}
			$absentees = array_merge($absentees,$emails);
			$newusers = array_merge($newusers,array_diff($absentees, $attendeesList));
			$input['attendees'] = implode(',', $attendees);
			$input['absentees'] = implode(',', $absentees);
			if($minuteId = Request::get('minuteId'))
			{
				//update
				$minute = Minutes::where('id','=',$minuteId)->whereFiled('0')->where('created_by',Auth::id())->first();
				if(!$minute)
				{
					abort('403');
				}
				$input['updated_by'] = Auth::id();
				if($minute->update($input))
				{
					if(count($newusers))
					{
						foreach ($newusers as $key => $value) 
						{
							if(isEmail($value))
							{
								$newusersList[$value] = $value;
							}
							else
							{
								$newusersList[$value] = getProfile(['userId'=>$value])->name;
							}
						}
						//notify admin
						$notification['userId'] = getAdmin()->id;
						$notification['objectId'] = $meeting->id;
						$notification['objectType'] = 'meetinguser';
						$notification['subject'] ='new';
						$notification['isRead'] = '0';
						$notification['body'] = serialize($newusersList);
						setNotification($notification);
					}
				}
			}
			else
			{
				//create new
				$minute = $meeting->minutes()->whereFiled('0');
				if($minute->count())
				{
					abort('403');
				}
				else
				{
					$minute = NULL;
				}
				$input['created_by'] = $input['updated_by'] = Auth::id();
				$minute = New Minutes($input);
				if($meeting->minutes()->save($minute))
				{
					if(count($newusers))
					{
						foreach ($newusers as $key => $value) 
						{
							if(isEmail($value))
							{
								$newusersList[$value] = $value;
							}
							else
							{
								$newusersList[$value] = getProfile(['userId'=>$value])->name;
							}
						}
						//notify admin
						$notification['userId'] = getAdmin()->id;
						$notification['objectId'] = $meeting->id;
						$notification['objectType'] = 'meetinguser';
						$notification['subject'] ='new';
						$notification['isRead'] = '0';
						$notification['body'] = serialize($newusersList);
						setNotification($notification);
					}	
				}
			}
			return redirect('minute/'.$meeting->id.'/next');
			//return view('meetings.createMinute',['meeting'=>$meeting,'attendees'=>NULL,'minute'=>$minute]);
		}
		else
		{
			abort('403');
		}
		
	}

	public function draft($mid)
	{
		if($minute = Minutes::where('id','=',$mid)->whereFiled('0')->where('created_by','=',Auth::id())->first())
		{
			if(!$minute->meeting->isMinuter())
			{
				abort('403');
			}
			$input = Request::only('tid','title','description','assignee','assigner','orginator','dueDate','type');
			$records=array();
			for ($i=0; $i < count($input['title']); $i++)
			{
				if($input['tid'][$i])
				{
					//skip the task of privious minutes
					continue;
				}
				$tempArr=array();
				$tempArr['title'] = trim($input['title'][$i]);
				$tempArr['description'] = trim($input['description'][$i]);
				$tempArr['assignee'] = $input['assignee'][$i];
				//$tempArr['assigner'] = $input['assigner'][$i];
				$tempArr['assigner'] = Auth::id();
				$tempArr['orginator'] = $input['orginator'][$i];
				$tempArr['dueDate'] = $input['dueDate'][$i];
				if(!isset($input['type'][$i]))
				{
					$input['type'][$i] = "task";
				}
				$tempArr['type'] = $input['type'][$i];
				$tempArr['created_by'] = Auth::id();
				if(($tempArr['title']) || ($tempArr['description']))
				$records[] = new MinuteDraft(array_filter($tempArr));
			}			
			if($records)
			{
				$minute->draft()->delete();
				if($minute->draft()->saveMany($records))
				{
					//echo "done"; die;
					return 'success';
				}
				else
				{
					abort('404','Insertion failed');
				}
			}
			else
			{
				return 'No data submited';
			}
		}
		else
		{
			abort('403');
		}
	}
	public function viewMinute($id)
	{
		if($minute = Minutes::whereId($id)->first())
		{
			$minutes = Minutes::where('meetingId','=',$minute->meetingId)->orderBy('created_at','desc')->get();
			return view('meetings.minuteHistory',['minute'=>$minute,'minutes'=>$minutes]);
		}
	}
	public function startMinute($id)
	{
		if($meeting = Meetings::whereId($id)->first())
		{
			return view('meetings.firstMinute',['meeting'=>$meeting]);
		}
	}
}
