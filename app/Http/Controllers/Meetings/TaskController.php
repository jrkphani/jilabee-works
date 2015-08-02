<?php namespace App\Http\Controllers\Meetings;
use App\Http\Controllers\Controller;
use Request;
use App\Model\MinuteTasks;
use App\Model\Minutes;
use App\Model\Ideas;
use App\Model\MinuteTaskComments;
use Auth;
use Activity;
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
		$input = Request::only('title','description','assignee','assigner','orginator','dueDate','type');
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
					$tempArr['created_by'] = $tempArr['updated_by'] = Auth::id();
					$validator = MinuteTasks::validation($tempArr);
					if ($validator->fails())
					{
						$output['success'] = 'no';
						$output['validator'] = $validator->messages()->toArray();
						return json_encode($output);
					}
					//if(($tempArr['title']) && ($tempArr['description']))
					$records[] = new MinuteTasks(array_filter($tempArr));	
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
			$minute = Minutes::whereId($mid)->where('lock_flag','=',Auth::id())->first();
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
			$minute->update(array('lock_flag'=>null));
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
	public function viewFollowup($mid,$id)
	{
		$task = MinuteTasks::whereIdAndAssigner($id,Auth::id())->where('minuteId',$mid)->first();
		return view('jobs.followupTask',['task'=>$task]);
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
		$task = MinuteTasks::whereIdAndAssigneeAndStatus($id,Auth::id(),'Sent')->where('minuteId',$mid)->first();
		$output['success'] = 'no';
		if($task)
		{
			$task->status = 'open';
			$task->reason = NULL;
			if($task->save())
				{
					$output['success'] = 'yes';
					Activity::log([
						'userId'	=> Auth::id(),
						'contentId'   => $task->id,
					    'contentType' => 'Minute Task',
					    'action'      => 'Accepted',
					    //'description' => 'Add Organizations User',
					    //'details'     => 'Rejected Reason: '.$input['reason']
					]);
					return redirect('jobs/mytask');
				}
		}
		else
		{
			return abort('403');
		}		
	}
	public function rejectTask($mid,$id)
	{
		$input = Request::only('reason');
		$output['success'] = 'no';
		if($input['reason'])
		{
			$task = MinuteTasks::whereIdAndAssigneeAndStatus($id,Auth::id(),'Sent')->where('minuteId',$mid)->first();
			$task->status = 'rejected';
			$task->reason = nl2br($input['reason']);
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
			}
			$output['success'] = 'yes';
			//return view('jobs.task',['task'=>$task]);		
		}
		else
		{
			$output['msg'] = 'Reason required';
			//$task = MinuteTasks::find($id);
			//return view('jobs.task',['task'=>$task,'reason_err'=>'Reason for rejection is require']);
		}
		return json_encode($output);
		
	}
	public function markComplete($mid,$id)
	{
		$task = MinuteTasks::whereIdAndAssigneeAndStatus($id,Auth::id(),'Open')->whereAssignee(Auth::id())->first();
		if($task)
		{
			$task->status = 'Completed';
			if($task->save())
			{
				return view('jobs.task',['task'=>$task]);
			}
		}
		else
		{
			abort('403');
		}
	}
	public function acceptCompletion($mid,$id)
	{
			$task = MinuteTasks::whereIdAndAssigner($id,Auth::id())->where('minuteId',$mid)->first();
			if($task)
			{
				$task->status = 'Closed';
				if($task->save())
				{
					return view('jobs.followupTask',['task'=>$task]);	
				}
			}
			else
			{
				abort('403');
			}
	}
	public function rejectCompletion($mid,$id)
	{
			$task = MinuteTasks::whereIdAndAssigner($id,Auth::id())->where('minuteId',$mid)->first();
			if($task)
			{
				$task->status = 'Open';
				if($task->save())
				{
					return view('jobs.followupTask',['task'=>$task]);	
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
	public function followupComment($mid,$id)
	{
		$input = Request::only('description');
		$validator = MinuteTaskComments::validation($input);
		if ($validator->fails())
		{
			return view('jobs.task',['task'=>$task])->withErrors($validator)->withInput($input);
		}
		$task = MinuteTasks::whereIdAndAssigner($id,Auth::id())->where('minuteId',$mid)->first();
		if($task)
		{
			$input['created_by'] = $input['updated_by'] = Auth::id();
			$input['description'] = nl2br($input['description']);
			$comment = new MinuteTaskComments($input);
			if($task->comments()->save($comment))
			{
				return view('jobs.followupTask',['task'=>$task]);
			}
		}
		else
		{
			abort('403');
		}
	}
}
