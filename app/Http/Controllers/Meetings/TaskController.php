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
	public function createTask($mid)
	{
		$input = Request::only('tid','title','description','assignee','assigner','orginator','dueDate','type');
		$output['success'] = 'yes';
		$records= $ideasArr = array();
		$updatedFlag = 0;
		$minute = Minutes::whereId($mid)->whereFiled('0')->where('created_by','=',Auth::id())->first();
		if($minute)
		{
			//make sure only minute which is not filed can be edited
			foreach (Request::get('removeTask',array()) as $taskId)
			{
				//remove the task while the minute is not filed yet
				$task = MinuteTasks::find($taskId);
				$task->comments()->forceDelete();
				$task->forceDelete();
				$notification['objectId'] = $task->id;
				$notification['objectType'] = 'Task';
				removeNotification($notification);
				//JobDraft::destroy(Request::input('id'));
			}
			foreach (Request::get('removeIdea',array()) as $ideaId)
			{
				//remove the idea while the minute is not filed yet
				$idea = Ideas::find($ideaId);
				$idea->forceDelete();
			}
			for ($i=0; $i < count($input['title']); $i++)
			{
				$tempArr= $notification = array();
				$tempArr['title'] = trim($input['title'][$i]);
				$tempArr['description'] = trim($input['description'][$i]);
				if(($tempArr['title']) && ($tempArr['description']))
				{
					if($input['type'][$i] == 'task')
					{
						$tempArr['assignee'] = $notification['userId'] = $input['assignee'][$i];
						//$tempArr['assigner'] = $input['assigner'][$i];
						$tempArr['assigner'] = Auth::id();
						$tempArr['dueDate'] = $input['dueDate'][$i];
						$tempArr['updated_by'] = Auth::id();
						$tempArr['status'] = 'Sent';
						$validator = MinuteTasks::validation($tempArr);
						if ($validator->fails())
						{
							$output['success'] = 'no';
							$output['validator'] = $validator->messages()->toArray();
							return json_encode($output);
						}
						if($assignee = getUser(['email'=>$input['assignee'][$i]]))
						{
							//check user has an account
							$input['assignee'][$i] = $notification['userId'] = $assignee->id;
						}
						else if(isEmail($input['assignee'][$i]))
						{
							//mark the task as accepted for who do have an account
							$tempArr['status'] = 'Open';
							$notification['userId']=0;
						}
						if($input['tid'][$i])
						{ 
							$findstate = 'status'.$input['tid'][$i];
							if(Request::get($findstate,null))
							{
								$tempArr['status'] = Request::get($findstate);
							}
							$updatedFlag = 1;
							$oldTask = MinuteTasks::whereId($input['tid'][$i]);
							if(($notification['userId']) && ($oldTask->first()->minuteId != $mid))
							{
								$notification['parentId'] = $oldTask->first()->minuteId;
								$notification['objectId'] = $input['tid'][$i];
								$notification['objectType'] = 'Task';
								$notification['subject'] = 'New';
								$notification['body'] = $tempArr['title'];
								setNotification($notification);
							}
							$oldTask->update($tempArr);
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
						$tempArr['updated_by'] = Auth::id();
						$validator = Ideas::validation($tempArr);
						if ($validator->fails())
						{
							$output['success'] = 'no';
							$output['validator'] = $validator->messages()->toArray();
							return json_encode($output);
						}
						if($input['tid'][$i])
						{
							$updatedFlag = 1;
							Ideas::whereId($input['tid'][$i])->update($tempArr);
						}
						else
						{
							$tempArr['created_by'] = Auth::id();
							$ideasArr[] = new Ideas(array_filter($tempArr));
						}
					}
				}
				
			}
			if($records || $ideasArr)
			{	
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
						foreach ($minute->tasks as $task) {
							if(!isEmail($task->assignee))
							{
								$notification['userId'] = $task->assignee;
								$notification['parentId'] = $task->minuteId;
								$notification['objectId'] = $task->id;
								$notification['objectType'] = 'Task';
								$notification['subject'] = 'New';
								$notification['body'] = $task->title;
								setNotification($notification);
							}
						}
					});
				}
				$minute->draft()->delete();
				$output['meetingId'] = $mid;
				//file minutes if no new task
				//$this->fileMinute($minute->meetingId);
				return json_encode($output);

			}
			else
			{
				if($updatedFlag == 1)
				{
					//file minutes if no new task
					$this->fileMinute($minute->meetingId);
					$output['success'] = 'yes';
					$output['meetingId'] = $mid;
				}
				else
				{
					$output['success'] = 'no';
					$output['message'] = 'Empty fields';
				}
				return json_encode($output);
			}
		}
		else
		{
			abort('403');
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
			$notification['userId'] = $task->assigner;
			$notification['parentId'] = $task->minuteId;
			$notification['objectId'] = $task->id;
			$notification['objectType'] = 'Task';
			$notification['subject'] = 'Accepted';
			$notification['body'] = $task->title;
			setNotification($notification);
			// Activity::log([
			// 	'userId'	=> Auth::id(),
			// 	'contentId'   => $task->id,
			// 	'contentType' => 'Minute Task',
			// 	'action'      => 'Accepted'
			// 	]);
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
				$notification['userId'] = $task->assigner;
				$notification['parentId'] = $task->minuteId;
				$notification['objectId'] = $task->id;
				$notification['objectType'] = 'Task';
				$notification['subject'] = 'Rejected';
				$notification['body'] = $task->title;
				setNotification($notification);
				// Activity::log([
				// 	'userId'	=> Auth::id(),
				// 	'contentId'   => $task->id,
				//     'contentType' => 'Minute Task',
				//     'action'      => 'Rejected',
				//     //'description' => 'Add Organizations User',
				//     'details'     => 'Rejected Reason: '.$input['reason']
				// ]);
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
							//echo $notAccepted; die;
			if(!$notAccepted)
			{
				$currentMinute = Minutes::where('meetingId',$meetingId)->where('filed','0')->first();
				//print_r($currentMinute->id); die;
				$lastFiledMinute = Minutes::where('meetingId',$meetingId)->where('filed','=','1')->orderBy('startDate', 'DESC')->limit(1)->first();
				$datenow = date('Y-m-d H:i:s');
				if($lastFiledMinute)
				{
					$tasks = Minutes::select(DB::raw("concat($currentMinute->id,'','') as minuteId , concat('$datenow','','') as created_at"),
								'minuteTasks.id as taskId','minuteTasks.title','minuteTasks.description','minuteTasks.assignee','minuteTasks.assigner','minuteTasks.status','minuteTasks.dueDate')
							//->where('minutes.meetingId',$meetingId)
							->where('minutes.id',$currentMinute->id)
							->join('minuteTasks','minuteTasks.minuteId','=','minutes.id')
							->orWhereIn('minuteTasks.id',function($query) use ($lastFiledMinute){
								$query->select('taskId')
		                    		->from('filedMinutes')
		                       		->where('filedMinutes.status','!=','Closed')
		                       		->where('filedMinutes.status','!=','Cancelled')
		                       		->where('filedMinutes.minuteId','=',$lastFiledMinute->id);
							})
							->get()->toArray();
				}
				else
				{
					$tasks = Minutes::select(DB::raw("concat($currentMinute->id,'','') as minuteId , concat('$datenow','','') as created_at"),
						'minuteTasks.id as taskId','minuteTasks.title','minuteTasks.description','minuteTasks.assignee','minuteTasks.assigner','minuteTasks.status','minuteTasks.dueDate')
							//->where('minutes.meetingId',$meetingId)
							->where('minutes.id',$currentMinute->id)
							->join('minuteTasks','minuteTasks.minuteId','=','minutes.id')
							->get()->toArray();	
				}
				if(FiledMinutes::insert($tasks))
				{
					$currentMinute->filed='1';
					$currentMinute->save();
				}
			}
	}
}
