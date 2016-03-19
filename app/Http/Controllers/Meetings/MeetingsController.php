<?php namespace App\Http\Controllers\Meetings;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\TempMeetings;
use App\Model\Meetings;
use App\Model\Minutes;
use App\Model\MinuteTasks;
use App\Model\Ideas;
use App\Model\Organizations;
use App\Model\Profile;
use App\User;
use Auth;
use DB;
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
		$query = Meetings::select('meetings.*')
						->join('organizations','meetings.oid','=','organizations.id')
						//->join('minutes','meetings.id','=','minutes.meetingId')
						->where('meetings.active','=','1')
						->whereNull('meetings.deleted_at')
						->where('organizations.customerId','=',getOrgId())
						->whereRaw('FIND_IN_SET("'.Auth::id().'",meetings.minuters)');
						$meetings =  $query->get();
		return view('meetings.index',['meetings'=>$meetings]);
	}
	public function view($meetingId)
	{	
		if(Meetings::where('id',$meetingId)->whereRaw('FIND_IN_SET("'.Auth::id().'",meetings.minuters)')->count())
		{
			$query = Minutes::select('minutes.*')->whereRaw('FIND_IN_SET("'.Auth::id().'",minutes.attendees)')
					->join('meetings','minutes.meetingId','=','meetings.id')
					->where('meetings.active','=','1')
					->where('meetings.id','=',$meetingId)
					->whereNull('meetings.deleted_at')
					//->where('minutes.filed','=','1')
					->groupBy('minutes.meetingId')
					->orderBy('minutes.startDate','desc');
			$minutes = $query->get();
			return view('meetings.minuteHistory',['minutes'=>$minutes]);
		}
		else
		{
			return abort('403');
		}
	}
	public function meetingForm($mid=NULL)
	{
		if($mid)
		{
			$meeting = TempMeetings::find($mid);
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
		$meetingInput = Request::only('meetingDescription','meetingTitle','meetingType',
								'venue','purpose','startDate','endDate','purpose');
		$minuteInput = Request::only('title','description','type','assignee','dueDate','orginator');
		$output['success'] = 'yes';
		$attendeesEmail=$attendees=array();
		$validator = TempMeetings::validation($meetingInput);
		if ($validator->fails())
		{
			$output['success'] = 'no';
			$output['validator'] = $validator->messages()->toArray();
			return json_encode($output);
		}
		else
		{
			for ($i=0; $i < count($minuteInput['title']); $i++)
			{
				$tempArr['title'] = trim($minuteInput['title'][$i]);
				$tempArr['description'] = trim($minuteInput['description'][$i]);
					if($minuteInput['type'][$i] == 'task')
					{
						$tempArr['assignee'] = $minuteInput['assignee'][$i];
						$tempArr['dueDate'] = $minuteInput['dueDate'][$i];
						$tempArr['status'] = 'Sent';
						$validator = MinuteTasks::validation($tempArr);
						if ($validator->fails())
						{
							$output['success'] = 'no';
							$output['validator'] = $validator->messages()->toArray();
							return json_encode($output);
						}	
					}
					elseif($minuteInput['type'][$i] == 'idea')
					{
						$validator = Ideas::validation($tempArr);
						if ($validator->fails())
						{
							$output['success'] = 'no';
							$output['validator'] = $validator->messages()->toArray();
							return json_encode($output);
						}
					}
			}
			if(getOrgId())
			{
				$data = ['title'=>$meetingInput['meetingTitle']
					,'description'=>$meetingInput['meetingDescription'],
					'purpose'=>$meetingInput['purpose'],
					'type'=>$meetingInput['meetingType'],
					'startDate'=>$meetingInput['startDate'],
					'endDate'=>$meetingInput['endDate'],
					'minuters'=>Auth::id(),
					'created_by'=>Auth::id(),
					'updated_by'=>Auth::id(),
					//'created_at'=>date('Y-m-d H:i:s'),
					//'updated_at'=>date('Y-m-d H:i:s'),
					'details'=>serialize($minuteInput),
					'draft'=>'0',
					'oid'=> Organizations::where('customerId','=',getOrgId())->first()->id];
				if(Request::get('id',null))
				{
					TempMeetings::whereId(Request::get('id'))->update($data);
				}
				else
				{
					TempMeetings::create($data);
				}
				return json_encode($output);
			}
			else
			{
				$data = ['title'=>$meetingInput['meetingTitle']
					,'description'=>$meetingInput['meetingDescription'],
					'purpose'=>$meetingInput['purpose'],
					'type'=>$meetingInput['meetingType'],
					'startDate'=>$meetingInput['startDate'],
					'endDate'=>$meetingInput['endDate'],
					'minuters'=>Auth::id(),
					'created_by'=>Auth::id(),
					'updated_by'=>Auth::id(),
					'oid'=> NULL];
				return $this->selfMeeting($data,$minuteInput);
			}
		}
	}
	public function draftMeeting()
	{
		$meetingInput = Request::only('meetingDescription','meetingTitle','meetingType',
								'venue','purpose','startDate','endDate','purpose');
		$minuteInput = Request::only('title','description','type','assignee','dueDate','orginator');
		$output['success'] = 'yes';
		$attendeesEmail=$attendees=array();
		$validator = TempMeetings::validation($meetingInput);
		if ($validator->fails())
		{
			$output['success'] = 'no';
			$output['validator'] = $validator->messages()->toArray();
		}
		else
		{
			$data = ['title'=>$meetingInput['meetingTitle']
					,'description'=>$meetingInput['meetingDescription'],
					'purpose'=>$meetingInput['purpose'],
					'type'=>$meetingInput['meetingType'],
					'startDate'=>$meetingInput['startDate'],
					'endDate'=>$meetingInput['endDate'],
					'minuters'=>Auth::id(),
					'created_by'=>Auth::id(),
					'updated_by'=>Auth::id(),
					//'created_at'=>date('Y-m-d H:i:s'),
					//'updated_at'=>date('Y-m-d H:i:s'),
					'details'=>serialize($minuteInput),
					'draft'=>'1'];
			if(getOrgId())
			{
				$data['oid'] = Organizations::where('customerId','=',getOrgId())->first()->id;
			}
			else
			{
				$data['oid'] = null;
			}
			if(Request::get('id',null))
				{
					$meeting = TempMeetings::whereId(Request::get('id'))->update($data);
					$output['meetingId'] = Request::get('id');
				}
				else
				{
					$meeting = TempMeetings::create($data);
					$output['meetingId'] = $meeting->id;
				}
		}
		return json_encode($output);
	}
	public function findMeeting()
	{	 
		$input = Request::only('term');
		$list = Meetings::select('meetings.id','meetings.title as value')->join('organizations','meetings.oid','=','organizations.id')
					->where('organizations.customerId','=',getOrgId())->get();
		return response()->json($list);
	}
	public function nowsortby()
	{
		$nowsearchtxt = Request::get('nowsearchtxt',NULL);
		$query = Meetings::select('meetings.*')
						->join('organizations','meetings.oid','=','organizations.id')
						//->join('minutes','meetings.id','=','minutes.meetingId')
						->where('meetings.active','=','1')
						->whereNull('meetings.deleted_at')
						->where('organizations.customerId','=',getOrgId())
						->whereRaw('FIND_IN_SET("'.Auth::id().'",meetings.minuters)')
						->whereNotExists(function($query)
						 	{
						 	$query->select(DB::raw(1))
		                    		->from('minutes')
		                       		->whereRaw('meetings.id = minutes.meetingId');
						 		});
		if($nowsearchtxt)
		{
			$query = $query->where(function($qry) use ($nowsearchtxt){
						$qry->where("meetings.title","LIKE","%$nowsearchtxt%");
						// ->orWhere("tasks.title","LIKE","%$nowsearchtxt%")
						// ->orWhere("tasks.description","LIKE","%$nowsearchtxt%");
					});
		}
		$minutes['newmeetings'] = $query->get();
						//print_r($newmeetings); die;
		$query = Minutes::select('minutes.*')->whereRaw('FIND_IN_SET("'.Auth::id().'",minutes.attendees)')
					->join('meetings','minutes.meetingId','=','meetings.id')
					->where('meetings.active','=','1')
					->whereNull('meetings.deleted_at')
					->where('minutes.filed','=','1')
					->groupBy('minutes.meetingId')
					->orderBy('minutes.startDate','desc');
		if($nowsearchtxt)
		{
			$query = $query->where(function($qry) use ($nowsearchtxt){
						$qry->where("meetings.title","LIKE","%$nowsearchtxt%");
					});
		}
		$minutes['recentMinutes'] = $query->get();
					//print_r(count($recentMinutes)); die;
		$query = Minutes::select('minutes.*')->whereRaw('FIND_IN_SET("'.Auth::id().'",minutes.attendees)')
					->join('meetings','minutes.meetingId','=','meetings.id')
					->where('meetings.active','=','1')
					->whereNull('meetings.deleted_at')
					->where('minutes.filed','=','0')
					->groupBy('minutes.meetingId');
		if($nowsearchtxt)
		{
			$query = $query->where(function($qry) use ($nowsearchtxt){
						$qry->where("meetings.title","LIKE","%$nowsearchtxt%");
					});
		}
		$minutes['notfiled'] = $query->get();
					//non approve meeting minutes
		$minutes['pendingmeetings'] = TempMeetings::where('created_by','=',Auth::id())->get();
		if (Request::ajax())
		{
		    return view('meetings.now',['nowsearchtxt'=>$nowsearchtxt,'minutes'=>$minutes]);
		}
		else
		{
			return $minutes;
		}

	}
	public function historysortby()
	{
		//closed meetings
		$historysearchtxt = Request::get('historysearchtxt',NULL);
		//echo $historysearchtxt; die;
		$query = Meetings::where(function($qry) {
			$qry->whereNotNull('deleted_at')->orWhere('active','=','0');
		});
		if($historysearchtxt)
		{
			$query = $query->where(function($qry) use ($historysearchtxt){
						$qry->where("title","LIKE","%$historysearchtxt%");
					});
		}
		$meetings = $query->withTrashed()->get();
		if (Request::ajax())
		{
		    return view('meetings.history',['historysearchtxt'=>$historysearchtxt,'meetings'=>$meetings]);
		}
		else
		{
			return $meetings;
		}
	}
	public function selfMeeting($meetingData,$details)
	{
		$output['success'] = 'yes';
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
				$tempArr['updated_by'] = Auth::id();
				$tempArr['created_by'] = Auth::id();
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
				$tempArr['updated_by'] = Auth::id();
				$tempArr['created_by'] = Auth::id();
				$ideasArr[] = New Ideas(array_filter($tempArr));
			}
		}					
		$meetingData['attendees'] =implode(',', $attendees);
		$meetingData['requested_by'] =Auth::id();
		$meetingData['created_by'] = $meetingData['updated_by'] =Auth::id();
		DB::transaction(function() use ($meetingData,$records,$ideasArr,$attendees)
		{
			if($newmeeting = Meetings::create($meetingData))
			{
				$attendees[] = Auth::id();
				$minute = New Minutes(['startDate'=>$meetingData['startDate'],
					'created_by'=>Auth::id(),'updated_by'=>Auth::id(),
					'endDate'=>$meetingData['endDate'],'attendees'=>implode(',',$attendees)]);
				$minute = $newmeeting->minutes()->save($minute);
				$minute->tasks()->saveMany($records);
				$minute->ideas()->saveMany($ideasArr);
				if(Request::get('id',null))
				{
					TempMeetings::whereId(Request::get('id'))->delete();
				}
			}
		});			
		return json_encode($output);
	}
	public function isReadNotification()
	{
		$notification['userId'] = Auth::id();
		$notification['objectId'] = Request::get('mid');
		$notification['objectType'] = 'meeting';
		readNotification($notification);
	}
}
