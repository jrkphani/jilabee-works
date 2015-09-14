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
		$recentMinutes = $notfiled = $pendingmeetings = $closedMeetings =array();
		$newmeetings = Meetings::select('meetings.*')
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
						 		})
						->get();
						//print_r($newmeetings); die;
		$recentMinutes = Minutes::select('minutes.*')->whereRaw('FIND_IN_SET("'.Auth::id().'",minutes.attendees)')
					->join('meetings','minutes.meetingId','=','meetings.id')
					->where('meetings.active','=','1')
					->whereNull('meetings.deleted_at')
					->where('minutes.filed','=','1')
					->groupBy('minutes.meetingId')
					->orderBy('minutes.startDate','desc')
					->get();
					//print_r(count($recentMinutes)); die;
		$notfiled = Minutes::select('minutes.*')->whereRaw('FIND_IN_SET("'.Auth::id().'",minutes.attendees)')
					->join('meetings','minutes.meetingId','=','meetings.id')
					->where('meetings.active','=','1')
					->whereNull('meetings.deleted_at')
					->where('minutes.filed','=','0')
					->groupBy('minutes.meetingId')
					->get();
					//non approve meeting minutes
		$pendingmeetings = TempMeetings::where('created_by','=',Auth::id())->get();

		//closed meetings
		$closedMeetings = Minutes::select('minutes.*')->whereRaw('FIND_IN_SET("'.Auth::id().'",minutes.attendees)')
					->join('meetings','minutes.meetingId','=','meetings.id')
					->where('meetings.active','=','1')
					->whereNotNull('meetings.deleted_at')
					->where('minutes.filed','=','1')
					->groupBy('minutes.meetingId')
					->orderBy('minutes.startDate','desc')
					->get();		
		return view('meetings.index',['notfiled'=>$notfiled,'recentMinutes'=>$recentMinutes,'newmeetings'=>$newmeetings,'pendingmeetings'=>$pendingmeetings,'closedMeetings'=>$closedMeetings]);
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
			$data = ['title'=>$meetingInput['meetingTitle']
					,'description'=>$meetingInput['meetingDescription'],
					'purpose'=>$meetingInput['purpose'],
					'type'=>$meetingInput['meetingType'],
					'startDate'=>$meetingInput['startDate'],
					'endDate'=>$meetingInput['endDate'],
					'minuters'=>Auth::id(),
					'created_by'=>Auth::id(),
					'updated_by'=>Auth::id(),
					'created_at'=>date('Y-m-d H:i:s'),
					'updated_at'=>date('Y-m-d H:i:s'),
					'details'=>serialize($minuteInput),
					'draft'=>'0',
					'oid'=> Organizations::where('customerId','=',getOrgId())->first()->id];
			if(Request::get('id',null))
				{
					TempMeetings::whereId(Request::get('id'))->update($data);
				}
				else
				{
					TempMeetings::insert($data);
				}
		}
		return json_encode($output);
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
					'draft'=>'1',
					'oid'=> Organizations::where('customerId','=',getOrgId())->first()->id];
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
	// public function loadMeeting($mid)
	// {
	// 	$tempMeetings = TempMeetings::where('id','=',$mid)
	// 			->where('created_by','=',Auth::id())->first();
	// 	if($tempMeetings)
	// 	{
	// 		return view('meetings.tempMeeting',['tempMeetings'=>$tempMeetings]);
	// 	}
	// 	else
	// 	{
	// 		abort('404');
	// 	}	
	// }
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
