<?php namespace App\Http\Controllers\Followups;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\JobTaskComments;
use App\Model\Tasks;
use App\Model\MinuteTasks;
use App\Model\MinuteTaskComments;
use Auth;
use Validator;
use Session;
use App\Model\JobDraft;
use Activity;
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
		$tasks = Tasks::whereAssigner(Auth::id())->orderBy('dueDate')->get();
		$drafts = JobDraft::where('assigner','=',Auth::id())->orderBy('updated_at','desc')->get();
		$taskToFinsh = $taskNotFiled = $taskCompleted = $taskCancelled = array();
		$taskClosed['recent'] =$taskClosed['previous']= $taskClosed['lastWeek'] = array();
		foreach($tasks as $task)
		{
			if($task->type == 'minute')
			{
				if($task->minute->filed == '1')
				{
					if($task->status == 'Completed')
					{
						$taskCompleted[] = $task;
					}
					else if($task->status == 'Open')
					{
						$taskToFinsh[] = $task;
					}
					else if($task->status == 'Closed')
					{
						$days = date('d',(strtotime(date('Y-m-d H:i:s')) - strtotime($task->updated_at)));
						if($days)
						{
							if($days <= 7)
							{
								//last 7 dyas
								$taskClosed['lastWeek'][]= $task;
							}
							else
							{
								$taskClosed['previous'][]= $task;
							}
						}
						else
						{
							$taskClosed['recent'][]= $task;
						}
					}
					else if($task->status == 'Cancelled')
					{
						$taskCancelled[] = $task;
					}
				}
				else
				{
					$taskNotFiled[] = $task;
				}
			}
			else
			{
				if($task->status == "Sent")
				{
					$taskNotFiled[] = $task;
				}
				else if($task->status == 'Completed')
				{
					$taskCompleted[] = $task;
				}
				else if($task->status == 'Open')
				{
					$taskToFinsh[] = $task;
				}
				else if($task->status == 'Closed')
				{
					$days = date('d',(strtotime(date('Y-m-d H:i:s')) - strtotime($task->updated_at)));
					if($days)
					{
						if($days <= 7)
						{
							//last 7 dyas
							$taskClosed['lastWeek'][]= $task;
						}
						else
						{
							$taskClosed['previous'][]= $task;
						}
					}
					else
					{
						$taskClosed['recent'][]= $task;
					}
				}
			}
			
		}
		return view('followups.index',['taskCancelled'=>$taskCancelled,'drafts'=>$drafts,'taskToFinsh'=>$taskToFinsh ,'taskNotFiled'=>$taskNotFiled,'taskCompleted'=>$taskCompleted,'taskClosed'=>$taskClosed]);
	}
	public function viewTask($id)
	{
		$task = JobTasks::whereId($id)->whereAssigner(Auth::id())->first();
		$notification['userId'] = $task->assigner;
		$notification['objectId'] = $task->id;
		$notification['objectType'] = 'Task';
		$notification['isRead'] = '1';
		$notification['body'] = $task->title;
		setNotification($notification);
		return view('followups.task',['task'=>$task]);
	}
	public function viewMinute($mid,$id)
	{
		$task = MinuteTasks::whereIdAndAssigner($id,Auth::id())->where('minuteId',$mid)->first();
		return view('followups.task',['task'=>$task]);
	}
	public function taskComment($id)
	{
		$input = Request::only('description');
		$validator = JobTaskComments::validation($input);
		$task = JobTasks::whereId($id)->whereAssigner(Auth::id())->first();
		if ($validator->fails())
		{
			return view('followups.task',['task'=>$task])->withErrors($validator)->withInput($input);
		}
		if($task)
		{
			$input['created_by'] = $input['updated_by'] = Auth::id();
			$input['description'] = nl2br($input['description']);
			$comment = new JobTaskComments($input);
			if($task->comments()->save($comment))
			{
				$notification['userId'] = $task->assignee;
				$notification['objectId'] = $task->id;
				$notification['objectType'] = 'Task';
				$notification['subject'] = 'Comment';
				$notification['body'] = $task->title;
				setNotification($notification);
				return view('followups.task',['task'=>$task]);
			}
		}
		else
		{
			abort('403');
		}
	}
	public function minuteComment($mid,$id)
	{
		$input = Request::only('description');
		$validator = MinuteTaskComments::validation($input);
		$task = MinuteTasks::whereIdAndAssigner($id,Auth::id())->where('minuteId',$mid)->first();
		if ($validator->fails())
		{
			return view('followups.task',['task'=>$task])->withErrors($validator)->withInput($input);
		}
		if($task)
		{
			$input['created_by'] = $input['updated_by'] = Auth::id();
			$input['description'] = nl2br($input['description']);
			$comment = new MinuteTaskComments($input);
			if($task->comments()->save($comment))
			{
				$notification['userId'] = $task->assignee;
				$notification['objectId'] = $task->id;
				$notification['parentId'] = $task->minuteId;
				$notification['objectType'] = 'Task';
				$notification['subject'] = 'Comment';
				$notification['body'] = $task->title;
				setNotification($notification);
				die;
				return view('followups.task',['task'=>$task]);
			}
		}
		else
		{
			abort('403');
		}
	}
	public function draft()
	{
		$input = Request::only('title','description','assignee','notes','dueDate');
		$input = array_filter($input);
		if($input)
		{
			$input['created_by'] = $input['assigner'] = Auth::id();
			if(isEmail($input['assignee']))
				{
					if($assignee = getUser(['email'=>$input['assignee']]))
					{
						$input['assignee'] = $assignee->id;
					}
				}
				else
				{
					if($assignee = getUser(['userId'=>$input['assignee']]))
					{
						$input['assignee'] = $assignee->id;
					}
					else
					{
						$input['assignee'] = NULL;	
					}
				}
			if(Request::input('id'))
			{
				$task = JobDraft::whereId(Request::input('id'))->first();
				$task->update($input);
				return view('jobs.draftform',['task'=>$task]);
			}
			else if($task = JobDraft::create($input))
			{
				return view('jobs.draftform',['task'=>$task]);
			}
			else
			{
				abort('404','Insertion failed');
			}
		}
		else
		{
			return view('jobs.draftform',['task'=>null]);
		}
	}
	public function draftform($id=null)
	{
		if($id)
		{
			return view('jobs.draftform',['task'=>JobDraft::whereId($id)->whereAssigner(Auth::id())->first()]);
		}
		else
		{
			return view('jobs.draftform',['task'=>null]);
		}
	}
	public function deleteDraft($id)
	{
			$task = JobDraft::whereId($id)->whereAssigner(Auth::id())->first();
			$output['success'] = 'no';
			if($task)
			{
				$task->delete();
				$output['success'] = 'yes';
			}
			else
			{
				abort('403');
			}
			return json_encode($output);
	}
}
