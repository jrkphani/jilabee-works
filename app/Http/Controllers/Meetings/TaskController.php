<?php namespace App\Http\Controllers\Meetings;
use App\Http\Controllers\Controller;
use Request;
use App\Model\MinuteTasks;
use App\Model\Minutes;
use App\Model\Ideas;
use App\Model\MinuteTaskComments;
use Auth;
use Activity;
use App\Model\FiledMinutes;
use DB;
use Validator;
class TaskController extends Controller {

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
		//return view('jobs.index');
	}

	public function createTask($mid)
	{
		$input = Request::only('tid','title','description','assignee','assigner','orginator','dueDate','type');
		$output['success'] = 'yes';
		$records= $ideasArr = array();
		for ($i=0; $i < count($input['title']); $i++)
		{
			$tempArr= array();
			$tempArr['title'] = trim($input['title'][$i]);
			$tempArr['description'] = trim($input['description'][$i]);
			if(($tempArr['title']) && ($tempArr['description']))
			{
				if(!isset($input['type'][$i]))
				{
					$input['type'][$i] = "task";
				}
				if($input['type'][$i] == 'task')
				{
					$tempArr['assignee'] = $input['assignee'][$i];
					$tempArr['assigner'] = $input['assigner'][$i];
					$tempArr['dueDate'] = $input['dueDate'][$i];
					$tempArr['updated_by'] = Auth::id();
					$validator = MinuteTasks::validation($tempArr);
					if ($validator->fails())
					{
						$output['success'] = 'no';
						$output['validator'] = $validator->messages()->toArray();
						return json_encode($output);
					}
					//if(($tempArr['title']) && ($tempArr['description']))
					if($input['tid'][$i])
					{
						MinuteTasks::whereId($input['tid'][$i])->update($tempArr);
					}
					else
					{
						$tempArr['created_by'] = Auth::id();
						$records[] = new MinuteTasks(array_filter($tempArr));
					}	
				}
				elseif($input['type'][$i] == 'idea')
				{
					$tempArr['orginator'] = $input['orginator'][$i];
					$tempArr['created_by'] = $tempArr['updated_by'] = Auth::id();
					$validator = Ideas::validation($tempArr);
					if ($validator->fails())
					{
						$output['success'] = 'no';
						$output['validator'] = $validator->messages()->toArray();
						return json_encode($output);
					}
					$ideasArr[] = new Ideas(array_filter($tempArr));
				}
			}
			
		}
		if($records || $ideasArr)
		{	
			$minute = Minutes::whereId($mid)->whereField('0')->where('created_by','=',Auth::id())->first();
			if($ideasArr)
			{
				DB::transaction(function() use ($minute,$ideasArr)
				{
					$minute->ideas()->saveMany($ideasArr);
				});
			}
			if($records)
			{
				DB::transaction(function() use ($minute,$records)
				{
					$minute->tasks()->saveMany($records);
				});
			}
			$minute->draft()->delete();
			$output['meetingId'] = $minute->meetingId;
			return json_encode($output);

		}
		else
		{
			$output['success'] = 'no';
			$output['message'] = 'Empty fields';
			return json_encode($output);
		}
	}
	public function viewTask($mid,$id)
	{
		$task = MinuteTasks::whereIdAndAssignee($id,Auth::id())->where('minuteId',$mid)->first();
		return view('jobs.task',['task'=>$task]);
	}
	public function taskForm($mid,$id)
	{
		return view('jobs.taskform',['task'=>MinuteTasks::whereId($id)->where('minuteId',$mid)
			->whereAssigner(Auth::id())->first()]);
	}
	public function viewHistory($mid,$id)
	{
		$task = MinuteTasks::whereId($id)->where('minuteId',$mid)->where(function($query)
			{
				$query->whereAssignerOrAssignee(Auth::id(),Auth::id());
			})->first();
		return view('jobs.historyTask',['task'=>$task]);
	}
	public function acceptTask($mid,$id)
	{
		// $lastFieldMinute = Minutes::whereNull('lock_flag')->where('field','=','1')->orderBy('startDate', 'DESC')->limit(1)->first();
		// $test = MinuteTasks::whereIn('id',function($query) use ($lastFieldMinute){
		// 						$query->select('taskId')
		//                     		->from('filedMinutes')
		//                        		->where('filedMinutes.minuteId','=',$lastFieldMinute->id);
		// 					})->get()->toArray();
		// print_r($test);
		// die; //test
		$task = MinuteTasks::whereId($id)->whereAssignee(Auth::id())->where('minuteId',$mid)
		->where(function($query)
			{
				$query->whereStatus('Sent')->orWhere('status','=','Rejected');

			})->first();
		$task->status = 'Open';
		$task->updated_by = Auth::id();
		if($task->save())
		{
			$this->fileMinute($task->minute->meetingId);
			Activity::log([
				'userId'	=> Auth::id(),
				'contentId'   => $task->id,
				'contentType' => 'Minute Task',
				'action'      => 'Accepted'
				]);
			$output['success'] = 'yes';
			return json_encode($output);
		}
	}
	public function rejectTask($mid,$id)
	{
		$input = Request::only('reason');
		$output['success'] = 'no';
		if($input['reason'])
		{
			$task = MinuteTasks::whereId($id)->where('status','=','Sent')->where('minuteId',$mid)
			->where('assignee','=',Auth::id())->first();
			$task->status = 'Rejected';
			$task->reason = $input['reason'];
			$task->updated_by = Auth::id();
			if($task->save())
			{
				Activity::log([
					'userId'	=> Auth::id(),
					'contentId'   => $task->id,
				    'contentType' => 'Minute Task',
				    'action'      => 'Rejected',
				    //'description' => 'Add Organizations User',
				    'details'     => 'Rejected Reason: '.$input['reason']
				]);
				$output['success'] = 'yes';
			}
			
		}
		else
		{
			$output['msg'] = 'Reason required';
			
		}
		return json_encode($output);
	}
	
	public function markComplete($mid,$id)
	{
		$task = MinuteTasks::whereIdAndAssigneeAndStatus($id,Auth::id(),'Open')->where('minuteId',$mid)->first();
		if($task)
		{
			$task->status = 'Completed';
			if($task->save())
			{
				$output['success'] = 'yes';
				return json_encode($output);
			}
		}
		else
		{
			abort('403');
		}
	}
	//has to complet in minutes only as per phani instruct
	// public function acceptCompletion($mid,$id)
	// {
	// 		$task = MinuteTasks::whereIdAndAssigner($id,Auth::id())->where('minuteId',$mid)->first();
	// 		if($task)
	// 		{
	// 			$task->status = 'Closed';
	// 			if($task->save())
	// 			{
	// 				return view('followups.task',['task'=>$task]);
	// 			}
	// 		}
	// 		else
	// 		{
	// 			abort('403');
	// 		}
	// }
	public function rejectCompletion($mid,$id)
	{
			$task = MinuteTasks::whereIdAndAssigner($id,Auth::id())->where('minuteId',$mid)->first();
			if($task)
			{
				$task->status = 'Open';
				if($task->save())
				{
					return view('followups.task',['task'=>$task]);
				}
			}
			else
			{
				abort('403');
			}
	}
	public function taskComment($mid,$id)
	{
		$input = Request::only('description');
		$validator = MinuteTaskComments::validation($input);
		$task = MinuteTasks::whereIdAndAssignee($id,Auth::id())->where('minuteId',$mid)->first();
		if ($validator->fails())
		{
			return view('jobs.task',['task'=>$task])->withErrors($validator)->withInput($input);
		}
		
		if($task)
		{
			$input['created_by'] = $input['updated_by'] = Auth::id();
			$input['description'] = nl2br($input['description']);
			$comment = new MinuteTaskComments($input);
			if($task->comments()->save($comment))
			{
				return view('jobs.task',['task'=>$task]);
			}
		}
		else
		{
			abort('403');
		}
	}
	public function fileMinute($meetingId)
	{
		//file meeting along with non closed task form previous minutes too.
			$notAccepted = Minutes::select('minuteTasks.id')->where('minutes.meetingId',$meetingId)
							->join('minuteTasks','minuteTasks.minuteId','=','minutes.id')
							->where(function($query)
							{
								$query->where('minuteTasks.status','=','Sent')
								->orWhere('minuteTasks.status','=','Rejected');
							})
							->count();
			if(!$notAccepted)
			{
				$currentMinute = Minutes::where('field','0')->first();
				$lastFieldMinute = Minutes::where('field','=','1')->orderBy('startDate', 'DESC')->limit(1)->first();
				if($lastFieldMinute)
				{
					$tasks = Minutes::select(DB::raw("concat($currentMinute->id,'','') as minuteId"),'minuteTasks.id as taskId','minuteTasks.title','minuteTasks.description','minuteTasks.assignee','minuteTasks.assigner','minuteTasks.status','minuteTasks.dueDate')
							//->where('minutes.meetingId',$meetingId)
							->where('minutes.id',$currentMinute->id)
							->join('minuteTasks','minuteTasks.minuteId','=','minutes.id')
							->orWhereIn('minuteTasks.id',function($query) use ($lastFieldMinute){
								$query->select('taskId')
		                    		->from('filedMinutes')
		                       		->where('filedMinutes.status','!=','Closed')
		                       		->where('filedMinutes.status','!=','Cancelled')
		                       		->where('filedMinutes.minuteId','=',$lastFieldMinute->id);
							})
							->get()->toArray();
				}
				else
				{
					$tasks = Minutes::select(DB::raw("concat($currentMinute->id,'','') as minuteId"),'minuteTasks.id as taskId','minuteTasks.title','minuteTasks.description','minuteTasks.assignee','minuteTasks.assigner','minuteTasks.status','minuteTasks.dueDate')
							//->where('minutes.meetingId',$meetingId)
							->where('minutes.id',$currentMinute->id)
							->join('minuteTasks','minuteTasks.minuteId','=','minutes.id')
							->get()->toArray();	
				}
				
				//print_r($tasks);
				//foreach ($tasks as $task)
				//{
				//	echo $task['title'];
				//	echo "<br>";
				//}
				//die;
							//yet to add closed task form previous minutes in fieldminutes
				if(FiledMinutes::insert($tasks))
				{
					$currentMinute->field='1';
					$currentMinute->save();
				}
			}
	}
}
