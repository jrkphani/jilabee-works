<?php namespace App\Http\Controllers\Meetings;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\Meetings;
use App\Model\MinuteTasks;
use App\Model\Minutes;
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
	public function create($mid)
	{
		if($meeting = Meetings::find($mid)->isMinuter())
		{
			//echo $meeting->minutes()->first()->lock_flag; die;
			if($meeting->minutes()->count() && ($meeting->minutes()->first()->lock_flag == Auth::id()))
			{
				$output['success'] = 'error';
				$output['msg'] = 'More than one minute';
				return json_encode($output);
			}
			$input = Request::only('venue','minuteDate','attendees');
			//print_r($input); die;
			$validator = Minutes::validation($input);
			if ($validator->fails())
			{
				$output['success'] = 'no';
				$output['validator'] = $validator->messages()->toArray();
			}
			else
			{
				$output['success'] = 'yes';
				$attendeesList =  array_merge(explode(',', $meeting->attendees),explode(',', $meeting->minuters));
				$input['attendees'][] = Auth::user()->id;
				$input['attendees'] = array_unique($input['attendees']);
				$absentees = array_diff($attendeesList, $input['attendees']);
				$input['attendees'] = implode(',', $input['attendees']);
				$input['absentees'] = implode(',', $absentees);
				$input['created_by'] = $input['updated_by'] = $input['lock_flag'] = Auth::id();
				$minute = New Minutes($input);
				$meeting->minutes()->save($minute);
				$output['mid'] = $mid.'/'.$minute->id;
			}
			return json_encode($output);
		}
		else
		{
			abort('403');
		}
		
	}
	public function update($mid)
	{
		if($minute = Minutes::where('id','=',$mid)->where('lock_flag','=',Auth::id())->first())
		{
			if(!$minute->meeting->isMinuter())
			{
				abort('403');
			}
			$input = Request::only('venue','minuteDate','attendees');
			$validator = Minutes::validation($input);
			if ($validator->fails())
			{
				$output['success'] = 'no';
				$output['validator'] = $validator->messages()->toArray();
			}
			else
			{
				$output['success'] = 'yes';
				$attendeesList =  array_filter(array_merge(explode(',', $minute->attendees),explode(',', $minute->minuters)));
				$input['attendees'][] = Auth::user()->id;
				$input['attendees'] = array_unique($input['attendees']);
				$absentees = array_diff($attendeesList, $input['attendees']);
				$input['attendees'] = implode(',', $input['attendees']);
				$input['absentees'] = implode(',', $absentees);
				$input['updated_by'] = $input['lock_flag'] = Auth::id();
				/*print_r($attendeesList);
				print_r($input); die;*/
				$minute->update($input);
			}
			return json_encode($output);
		}
		else
		{
			abort('403');
		}
		
	}
	public function draft($mid)
	{
		if($minute = Minutes::where('id','=',$mid)->where('lock_flag','=',Auth::id())->first())
		{
			if(!$minute->meeting->isMinuter())
			{
				abort('403');
			}
			$input = Request::only('title','description','assignee','assigner','orginator','dueDate','type');
			$records=array();
			for ($i=0; $i < count($input['title']); $i++)
			{
				$tempArr=array();
				$tempArr['title'] = trim($input['title'][$i]);
				$tempArr['description'] = trim($input['description'][$i]);
				$tempArr['assignee'] = $input['assignee'][$i];
				$tempArr['assigner'] = $input['assigner'][$i];
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
			$minutes = Minutes::where('meetingId','=',$minute->meetingId)->where('id','!=',$id)->get();
			return view('meetings.minuteHistory',['minute'=>$minute,'minutes'=>$minutes]);
		}
	}
}
