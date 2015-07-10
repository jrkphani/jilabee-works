<?php namespace App\Http\Controllers\Jobs;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\JobTaskComments;
use App\Model\Tasks;
use App\Model\MinuteTasks;
use Auth;
use App\Model\JobDraft;
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
		return view('jobs.index');
	}
	public function mytask()
	{
		$tasks = Tasks::where('assignee','=',Auth::id())
					->where('status','!=','Closed')->get();
		return view('jobs.mytask',['tasks'=>$tasks]);
	}
	public function viewTask($id)
	{
		$task = JobTasks::where('id','=',$id)->where('assignee','=',Auth::id())->first();
		return view('jobs.task',['task'=>$task]);
	}
	public function viewfollowup($id)
	{
		$task = JobTasks::where('id','=',$id)->where('assigner','=',Auth::id())->first();
		return view('jobs.followupTask',['task'=>$task]);
	}
	public function acceptTask($id)
	{
		$task = JobTasks::find($id)->where('status','=','Sent')->where('assignee','=',Auth::id())->first();
		$task->status = 'Open';
		$task->save();
		return view('jobs.task',['task'=>$task]);
	}
	public function rejectTask($id)
	{
		$input = Request::only('reason');
		if($input['reason'])
		{
			$task = JobTasks::find($id)->where('status','=','Sent')->where('assignee','=',Auth::id())->first();
			$task->status = 'Rejected';
			$task->reason = $input['reason'];
			$task->save();
			return view('jobs.task',['task'=>$task]);
		}
		else
		{
			$task = JobTasks::find($id);
			return view('jobs.task',['task'=>$task,'reason_err'=>'Reason for rejection is require']);
		}
	}
	public function followups()
	{
		$tasks = Tasks::where('assigner','=',Auth::id())
					->where('status','!=','Closed')->get();
		$drafts = JobDraft::where('assigner','=',Auth::id())->get();
		return view('jobs.followups',['tasks'=>$tasks,'drafts'=>$drafts]);
	}
	public function history()
	{
		$history = JobTasks::where('assignee','=',Auth::id())
					->orWhere('assigner','=',Auth::id())->get();
		return view('jobs.history',['history'=>$history]);
	}
	public function draft()
	{
		$input = Request::only('title','description','assignee','notes','dueDate');
		$input['created_by'] = $input['assigner'] = Auth::id();
		if(Request::input('id'))
		{
			$task = JobDraft::whereId(Request::input('id'))->first();
			$task->update($input);
			return view('jobs.taskform',['task'=>$task]);
		}
		else if($task = JobDraft::create($input))
		{
			return view('jobs.taskform',['task'=>$task]);
		}
		else
		{
			abort('404','Insertion failed');
		}
	}
	public function taskform($id=null)
	{
		if($id)
		{
			return view('jobs.taskform',['task'=>JobDraft::whereId($id)->whereAssigner(Auth::id())->first()]);
		}
		else
		{
			return view('jobs.taskform',['task'=>null]);
		}
	}
	public function createTask()
	{
		$input = Request::only('title','description','assignee','assigner','dueDate');
		if(!$input['assignee'])
			{
				$input['assignee'] = $input['assigneeEmail'];
			}
		$output['success'] = 'yes';
		$validator = JobTasks::validation($input);
		if ($validator->fails())
		{
			$output['success'] = 'no';
			$output['validator'] = $validator->messages()->toArray();
			return json_encode($output);
		}
		else
		{
			$input['created_by'] = $input['updated_by'] = $input['assigner'] = Auth::id();
			$task = JobTasks::create($input);
			//$task->tId = "T".$task->id;
			//$task->save();
			return json_encode($output);
		}
	}
	public function updateStatus($id)
	{
		$input = Request::only('status');
		$validator = JobTasks::validation($input);
		if ($validator->fails())
		{
			$output['success'] = "no";
			$output['validator'] = $validator->messages()->toArray();
		}
		else
		{
			$task = JobTasks::whereId($id)->whereAssignerOrAssignee(Auth::id(),Auth::id())->first();
			$task->status = $input['status'];
			$task->save();
			$output['success'] = "yes";
		}
		return json_encode($output);
	}
	public function comment($id)
	{
		$input = Request::only('description');
		$validator = JobTaskComments::validation($input);
		$task = JobTasks::whereId($id)->where(function ($query){
					$query->whereAssignerOrAssignee(Auth::id(),Auth::id());
					})->first();
		if ($validator->fails())
		{
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
			if($task)
			{
				$input['created_by'] = $input['updated_by'] = Auth::id();
				$input['description'] = nl2br($input['description']);
				$comment = new JobTaskComments($input);
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
}
