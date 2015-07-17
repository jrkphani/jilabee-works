<?php namespace App\Http\Controllers\Jobs;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\JobTaskComments;
use App\Model\Tasks;
use App\Model\MinuteTasks;
use App\Model\OtherTaskComments;
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
		$task = JobTasks::whereId($id)->whereAssigner(Auth::id())->first();
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
		$otherTask = OtherTasks::whereType('task')->whereId($id)->whereStatus('Sent')->whereAssignee(Auth::id())->first();
		if($input['reason'])
		{
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
			return view('jobs.otherTask',['task'=>$otherTask,'reason_err'=>'Reason for rejection is require']);
		}
	}
	public function followups()
	{
		$tasks = Tasks::whereAssigner(Auth::id())
					->where('status','!=','Closed')->orderBy('dueDate')->get();
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
			if(isEmail($input['assignee']))
			{
				if($assignee = getUser($input['assigneeEmail']))
				{
					if(starts_with(Auth::user()->userId, 'GEN'))
					{
						$userDB = env('GEN_DATABASE');
					}
					else
					{
						$userDB = substr($assignee->userId, 0, strrpos($assignee->userId, 'u'));
					}
					if(Session::get('database') == $userDB)
						{
							//same organiztion
							//alert to org admin
							$input['assignee'] = $assignee->id;
						}
						else
						{
							$otherUser = 1;
							//create task link to assinee user
						}
				}
			}
			$input['description'] = nl2br($input['description']);
			$input['notes'] = nl2br($input['notes']);
			$input['created_by'] = $input['updated_by'] = $input['assigner'] = Auth::id();
			if($task = JobTasks::create($input))
			{
				if($otherUser)
				{

					//other ORG database user
					configureConnection($userDB);
					$otherTasks = new OtherTasks();
					$otherTasks->setConnection($userDB);
					$otherTasks->taskId = $task->id;
					$otherTasks->type = 'task';
					$otherTasks->title = $input['title'];
					$otherTasks->description = $input['description'];
					$otherTasks->dueDate = $input['dueDate'];
					$otherTasks->assignee = $assignee->id;
					$otherTasks->assigner = Auth::user()->email;
					$otherTasks->org = Session::get('database');
					$otherTasks->created_by = Auth::id();
					$otherTasks->updated_by = Auth::id();
					$otherTasks->save();
				}
			}
			return json_encode($output);
		}
	}
	public function markComplete($id)
	{
		$task = JobTasks::whereId($id)->whereAssignee(Auth::id())->first();
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
	public function acceptCompletion($id)
	{
			$task = JobTasks::whereId($id)->whereAssigner(Auth::id())->first();
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
	public function rejectCompletion($id)
	{
			$task = JobTasks::whereId($id)->whereAssigner(Auth::id())->first();
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
	public function taskComment($id)
	{
		$input = Request::only('description');
		$validator = JobTaskComments::validation($input);
		$task = JobTasks::whereId($id)->whereAssignee(Auth::id())->first();
		if ($validator->fails())
		{
			return view('jobs.task',['task'=>$task])->withErrors($validator)->withInput($input);
		}
		if($task)
		{
			$input['created_by'] = $input['updated_by'] = Auth::id();
			$input['description'] = nl2br($input['description']);
			$comment = new JobTaskComments($input);
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
	public function otherTaskComment($id)
	{
		$input = Request::only('description');
		$validator = OtherTaskComments::validation($input);
		$otherTask = OtherTasks::whereId($id)->whereAssignee(Auth::id())->first();
		if ($validator->fails())
		{
			return view('jobs.otherTask',['task'=>$otherTask])->withErrors($validator)->withInput($input);
		}
		if($otherTask)
		{
			configureConnection($otherTask->org);
			$jobtask = new JobTasks;
			$task = $jobtask->setConnection($otherTask->org)->whereId($otherTask->taskId)->first();
			if($task)
				{
					//update orinal comment table
					$jobTaskComments = new JobTaskComments();
					$jobTaskComments->setConnection($otherTask->org);
					$jobTaskComments->description = $input['description'];
					$jobTaskComments->created_by = 2;
					$jobTaskComments->updated_by = 2;
					if($task->comments()->save($jobTaskComments))
					{
						//update clone comment table
						$input['created_by'] = $input['updated_by'] = Auth::id();
						$otherTaskComments = new otherTaskComments($input);
						$otherTask->comments()->save($otherTaskComments);
						return view('jobs.otherTask',['task'=>$otherTask]);
					}
				}
		}
		else
		{
			abort('403');
		}
	}
	public function followupComment($id)
	{
		$input = Request::only('description');
		$validator = JobTaskComments::validation($input);
		$task = JobTasks::whereId($id)->whereAssigner(Auth::id())->first();
		if ($validator->fails())
		{
			return view('jobs.followupTask',['task'=>$task])->withErrors($validator)->withInput($input);
		}
		if($task)
		{
			$input['created_by'] = $input['updated_by'] = Auth::id();
			$input['description'] = nl2br($input['description']);
			$comment = new JobTaskComments($input);
			if($task->comments()->save($comment))
			{
				if(isEmail($task->assignee))
				{
					if($assignee = getUser($task->assignee))
					{
						if(starts_with(Auth::user()->userId, 'GEN'))
						{
							$userDB = env('GEN_DATABASE');
						}
						else
						{
							$userDB = substr($assignee->userId, 0, strrpos($assignee->userId, 'u'));
						}
						//echo $userDB; die;
						configureConnection($userDB);
						$otherTasks = new OtherTasks;
						$otherTask = $otherTasks->setConnection($userDB)->whereType('task')->where('taskId',$id)->whereCreated_by(Auth::id())->first();
						if($otherTask)
						{
							$otherTaskComments = new OtherTaskComments();
							$otherTaskComments->setConnection($userDB);
							$otherTaskComments->description = $input['description'];
							$otherTaskComments->created_by = Auth::id();
							$otherTaskComments->updated_by = Auth::id();
							$otherTask->comments()->save($otherTaskComments);
						}
					}
				}
				return view('jobs.followupTask',['task'=>$task]);
			}
		}
		else
		{
			abort('403');
		}
	}
}
