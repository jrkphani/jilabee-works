<?php namespace App\Http\Controllers\Meetings;
use App\Http\Controllers\Controller;
use Request;
use App\Model\MinuteTasks;
use App\Model\Minutes;
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
		$input = Request::only('title','description','assignee','assigner','dueDate','type');
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
				else
				{
					// $tempArr['orginators'] = $input['orginators'][$i];
					// $tempArr['created_by'] = $tempArr['updated_by'] = Auth::user()->id;
					// $ideasArr[] = new Ideas(array_filter($tempArr));
				}
			}
			
		}
		if($records )
		{	
			//$minute->ideas()->saveMany($ideasArr);
			// if($minute->tasks()->saveMany($records))
			// {
			// 	$minute->update(array('lock_flag'=>'0'));
			// 	$minute->tasks_draft()->delete();
			// 	return redirect('/#meetings#minute'.$minute->id);
			// }
			DB::transaction(function() use ($mid,$records)
			{
				$minute = Minutes::where('id','=',$mid)->where('lock_flag','=',Auth::id())->first();
				$minute->tasks()->saveMany($records);
				$minute->update(array('lock_flag'=>null));
				$minute->draft()->delete();
				$output['meetingId'] = $minute->meetingId;
			});
			return json_encode($output);

		}
		else
		{
			$output['success'] = 'no';
			$output['success'] = 'Empty fields';
			return json_encode($output);
		}
	}
	public function viewTask($id)
	{
		$task = MinuteTasks::where('id','=',$id)->where('assignee','=',Auth::id())->first();
		return view('jobs.task',['task'=>$task]);
	}
	public function viewFollowup($id)
	{
		$task = MinuteTasks::where('id','=',$id)->where('assigner','=',Auth::id())->first();
		return view('jobs.followupTask',['task'=>$task]);
	}
	public function acceptTask($id)
	{
		$task = MinuteTasks::where('id','=',$id)->where('status','=','Sent')->where('assignee','=',Auth::id())->first();
		$task->status = 'open';
		$task->reason = NULL;
		if($task->save())
			{
				Activity::log([
					'userId'	=> Auth::id(),
					'contentId'   => $task->id,
				    'contentType' => 'Minute Task',
				    'action'      => 'Accepted',
				    //'description' => 'Add Organizations User',
				    //'details'     => 'Rejected Reason: '.$input['reason']
				]);
			}
		return view('jobs.task',['task'=>$task]);
	}
	public function rejectTask($id)
	{
		$input = Request::only('reason');
		if($input['reason'])
		{
			$task = MinuteTasks::where('id','=',$id)->where('status','=','Sent')->where('assignee','=',Auth::id())->first();
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
			return view('jobs.task',['task'=>$task]);		
		}
		else
		{
			$task = MinuteTasks::find($id);
			return view('jobs.task',['task'=>$task,'reason_err'=>'Reason for rejection is require']);
		}
		
	}
	public function comment($id)
	{
		$input = Request::only('description');
		$validator = MinuteTaskComments::validation($input);
		if ($validator->fails())
		{
			$task = MinuteTasks::find($id);
			if($task->assignee == Auth::id())
			{
				return view('jobs.task',['task'=>$task])->withErrors($validator)->withInput($input);
			}
			else
			{
				return view('jobs.followupTask',['task'=>$task])->withErrors($validator)->withInput($input);	
			}
		}
		else
		{
			$task = MinuteTasks::whereId($id)
				//->where('status','=','Open')
				->where('assignee','=',Auth::id())->orWhere('assigner','=',Auth::id())->first();
			if($task)
			{
				$input['created_by'] = $input['updated_by'] = Auth::id();
				$input['description'] = nl2br($input['description']);
				$comment = new MinuteTaskComments($input);
				$task->comments()->save($comment);
				if($task->assignee == Auth::id())
				{
					return view('jobs.task',['task'=>$task]);
				}
				else
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
	public function updateStatus($id)
	{
		$input = Request::only('status');
		$rule = array('status' => 'required|in:Open,Completed,Closed,Cancelled');
        $validator = Validator::make($input,$rule);
		if ($validator->fails())
		{
			$output['success'] = "no";
			$output['validator'] = $validator->messages()->toArray();
		}
		else
		{
			$task = MinuteTasks::whereId($id)->where(function ($query){
					$query->whereAssignerOrAssignee(Auth::id(),Auth::id());

					})->first();
			$task->status = $input['status'];
			$task->save();
			$output['success'] = "yes";
		}
		return json_encode($output);
	}
}
