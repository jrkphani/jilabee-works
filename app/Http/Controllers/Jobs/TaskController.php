<?php namespace App\Http\Controllers\Jobs;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\JobTaskComments;
use App\Model\Tasks;
use App\Model\MinuteTasks;
use Auth;
use Validator;
use Session;
use App\Model\JobDraft;
use App\Model\OtherTasks;
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
	public function viewOtherTask($id)
	{
		$task = OtherTasks::where('id','=',$id)->where('assignee','=',Auth::id())->first();
		return view('jobs.otherTask',['task'=>$task]);
	}
	public function viewfollowup($id)
	{
		$task = JobTasks::where('id','=',$id)->where('assigner','=',Auth::id())->first();
		return view('jobs.followupTask',['task'=>$task]);
	}
	public function acceptTask($id)
	{
		$task = JobTasks::whereId($id)->whereStatus('Sent')->whereAssignee(Auth::id())->first();
		$task->status = 'Open';
		$task->updated_by = Auth::id();
		$task->save();
		return view('jobs.task',['task'=>$task]);
	}
	public function acceptOtherTask($id)
	{
		$otherTask = OtherTasks::whereType('task')->whereId($id)->whereStatus('Sent')->whereAssignee(Auth::id())->first();
		if($otherTask)
		{
			configureConnection($otherTask->org);
			$jobtask = new JobTasks;
			$task = $jobtask->setConnection($otherTask->org)->whereId($otherTask->taskId)->first();
			$task->status = 'Open';
			//$task->updated_by = Auth::id();
			if($task->save())
			{
				$otherTask->status = 'Open';
				$otherTask->updated_by = Auth::id();
				$otherTask->save();
			}
			return view('jobs.otherTask',['task'=>$task]);
		}
		else
		{
			abort('403');
		}
	}
	public function rejectTask($id)
	{
		$input = Request::only('reason');
		if($input['reason'])
		{
			$task = JobTasks::whereId($id)->where('status','=','Sent')->where('assignee','=',Auth::id())->first();
			$task->status = 'Rejected';
			$task->reason = $input['reason'];
			$task->updated_by = Auth::id();
			$task->save();
			return view('jobs.task',['task'=>$task]);
		}
		else
		{
			$task = JobTasks::find($id);
			return view('jobs.task',['task'=>$task,'reason_err'=>'Reason for rejection is require']);
		}
	}
	public function rejectOtherTask($id)
	{
		$input = Request::only('reason');
		if($input['reason'])
		{
			$otherTask = OtherTasks::whereType('task')->whereId($id)->whereStatus('Sent')->whereAssignee(Auth::id())->first();
			if($otherTask)
			{
				configureConnection($otherTask->org);
				$jobtask = new JobTasks;
				$task = $jobtask->setConnection($otherTask->org)->whereId($otherTask->taskId)->first();
				$task->status = 'Rejected';
				//$task->updated_by = Auth::id();
				$task->reason = $input['reason'];
				if($task->save())
				{
					$otherTask->status = 'Rejected';
					$otherTask->updated_by = Auth::id();
					$otherTask->reason = $input['reason'];
					$otherTask->save();
				}
				return view('jobs.otherTask',['task'=>$task]);
			}
			else
			{
				abort('403');
			}
		}
		else
		{
			$task = OtherTasks::find($id);
			return view('jobs.otherTask',['task'=>$task,'reason_err'=>'Reason for rejection is require']);
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
		$input = Request::only('title','description','assignee','assigneeEmail','notes','dueDate');
		$input['created_by'] = $input['assigner'] = Auth::id();
		if(!$input['assignee'])
			{
				$input['assignee'] = $input['assigneeEmail'];
			}
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
		$input = Request::only('title','description','assignee','assigner','assigneeEmail','notes','dueDate');
		$otherUser = 0;
		if(!$input['assignee'])
			{
				$input['assignee'] = $input['assigneeEmail'];
				if($assignee = getUser($input['assigneeEmail']))
				{
					if(starts_with(Auth::user()->userId, 'GEN'))
					{
						//create task link to assinee user
						$otherUser = 1;
						$userDB = env('GEN_DATABASE');
					}
					else
					{
						$userDB = substr($assignee->userId, 0, strrpos($assignee->userId, 'u'));
						if(Session::get('database') == $userDB)
						{
							//same organiztion
							//alert to org admin
							$input['assignee'] = $assignee->id;
						}
						else
						{
							$otherUser = 2;
							//create task link to assinee user
						}
					}
				}
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
			if(Request::input('id'))
			{
				JobDraft::destroy(Request::input('id'));
			}
			$input['description'] = nl2br($input['description']);
			$input['notes'] = nl2br($input['notes']);
			$input['created_by'] = $input['updated_by'] = $input['assigner'] = Auth::id();
			if($task = JobTasks::create($input))
			{
				if($otherUser)
				{
					if($otherUser == 2)
					{
						//other ORG database user
						configureConnection($userDB);
					}
					/*else
					{
						//general database user
					}*/
					$otherTasks = new OtherTasks();
					$otherTasks->setConnection($userDB);
					$otherTasks->taskId = $task->id;
					$otherTasks->type = 'task';
					$otherTasks->title = $input['title'];
					$otherTasks->description = $input['description'];
					$otherTasks->title = $input['dueDate'];
					$otherTasks->assignee = $assignee->id;
					$otherTasks->assigner = Auth::user()->email;
					$otherTasks->org = Session::get('database');
					$otherTasks->created_by = Auth::id();
					$otherTasks->updated_by = Auth::id();
					$otherTasks->save();
				}
			}
			//$task->tId = "T".$task->id;
			//$task->save();
			return json_encode($output);
		}
	}
	public function updateStatus($id)
	{
		$input = Request::only('status');
		$rule = array('status' => 'required|in:Open,Completed,Closed,Cancelled');
        $validator = Validator::make($input,$rule);
		if ($validator->fails())
		{
			if($task->assignee == Auth::id())
			{
				return view('jobs.task',['task'=>$task])->withErrors($validator);
			}
			else
			{
				return view('jobs.followupTask',['task'=>$task])->withErrors($validator);	
			}
		}
		else
		{
			$task = JobTasks::whereId($id)->where(function ($query){
					$query->whereAssignerOrAssignee(Auth::id(),Auth::id());
					})->first();
			$task->status = $input['status'];
			$task->save();
			if($task->assignee == Auth::id())
			{
				return view('jobs.task',['task'=>$task])->withErrors($validator);
			}
			else
			{
				return view('jobs.followupTask',['task'=>$task])->withErrors($validator);	
			}
		}
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
