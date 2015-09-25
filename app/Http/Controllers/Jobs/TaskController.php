<?php namespace App\Http\Controllers\Jobs;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\JobTasksLog;
use App\Model\JobTaskComments;
use App\Model\Tasks;
use App\Model\MinuteTasks;
use Auth;
use Validator;
use Session;
use App\Model\JobDraft;
use Activity;
use DateTime;
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
		//echo  Auth::id(); die;
		//$tasks = Tasks::whereAssignee(Auth::id())->orderBy('dueDate')->get();
		$sortby = Request::get('sortby','timeline');
		//echo  $sortby; die;
		$mytask = array();
		//$taskToFinsh = $taskNotFiled = $taskCompleted = $taskClosed['recent'] =$taskClosed['previous']= $taskClosed['lastWeek'] = array();
		if($sortby == 'timeline')
		{
			$tasks = Tasks::whereAssignee(Auth::id())->orderBy('dueDate')->get();
		}
		elseif ($sortby == 'meeting') 
		{
			$tasks = Tasks::whereAssignee(Auth::id())->orderBy('minuteId')->get();
		}
		elseif ($sortby == 'assigner')
		{
			$tasks = Tasks::whereAssignee(Auth::id())->orderBy('assigner')->get();
		}
		$mytask = $this->sortbytime($sortby,$tasks);
		return view('jobs.index',['mytask'=>$mytask]);
	}
	public function viewTask($id)
	{
		$task = JobTasks::whereId($id)->whereAssignee(Auth::id())->first();
		$notification['userId'] = $task->assignee;
		$notification['objectId'] = $task->id;
		$notification['objectType'] = 'Task';
		$notification['isRead'] = '1';
		$notification['body'] = $task->title;
		setNotification($notification);
		return view('jobs.task',['task'=>$task]);
	}
	public function viewHistory($id)
	{
		$task = JobTasks::whereId($id)->where(function($query)
			{
				$query->whereAssignerOrAssignee(Auth::id(),Auth::id());
			})->first();
		return view('jobs.historyTask',['task'=>$task]);
	}
	public function acceptTask($id)
	{
		$task = JobTasks::whereId($id)->whereAssignee(Auth::id())->where(function($query)
			{
				$query->whereStatus('Sent')->orWhere('status','=','Rejected');

			})->first();
		$task->status = 'Open';
		$task->updated_by = Auth::id();
		if($task->save())
		{
			$notification['userId'] = $task->assigner;
			$notification['objectId'] = $task->id;
			$notification['objectType'] = 'Task';
			$notification['subject'] = 'Accepted';
			$notification['body'] = $task->title;
			setNotification($notification);
		}
		//return view('jobs.task',['task'=>$task]);
		//return redirect('jobs/mytask');
		$output['success'] = 'yes';
		return json_encode($output);
	}
	public function rejectTask($id)
	{
		$input = Request::only('reason');
		$output['success'] = 'no';
		if($input['reason'])
		{
			$task = JobTasks::whereId($id)->where('status','=','Sent')->where('assignee','=',Auth::id())->first();
			$task->status = 'Rejected';
			$task->reason = $input['reason'];
			$task->updated_by = Auth::id();
			if($task->save())
			{
				$notification['userId'] = $task->assigner;
				$notification['objectId'] = $task->id;
				$notification['objectType'] = 'Task';
				$notification['subject'] = 'Rejected';
				$notification['body'] = $task->title;
				setNotification($notification);
				
				// Activity::log([
				// 	'userId'	=> Auth::id(),
				// 	'contentId'   => $task->id,
				//     'contentType' => 'Task',
				//     'action'      => 'Rejected',
				//     //'description' => 'Add Organizations User',
				//     'details'     => 'Rejected Reason: '.$input['reason']
				// ]);
			}
			$output['success'] = 'yes';
		}
		else
		{
			$output['msg'] = 'Reason required';
			//$task = JobTasks::find($id);
			//return view('jobs.task',['task'=>$task,'reason_err'=>'Reason for rejection is require']);
		}
		return json_encode($output);
	}
	
	public function createTask()
	{
		$input = Request::only('title','description','assignee','assigner','notes','dueDate');
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
			$input['status'] = 'Sent';
			$notification['userId']=0;
			if(isEmail($input['assignee']))
			{
				if($assignee = getUser(['email'=>$input['assignee']]))
				{
					$input['assignee'] = $notification['userId'] = $assignee->id;
				}
				else
				{
					//mark the task as accepted for who do have an account
					$input['status'] = 'Open';
					$notification['userId']=0;
				}
			}
			else
			{
				if($assignee = getUser(['userId'=>$input['assignee']]))
				{
					$input['assignee'] = $notification['userId'] = $assignee->id;
				}
				else
				{
					$validator->errors()->add('assignee', 'Invalid assignee!');
					$output['success'] = 'no';
					$output['validator'] = $validator->messages()->toArray();
					return json_encode($output);
				}
			}
			$input['description'] = nl2br($input['description']);
			$input['notes'] = nl2br($input['notes']);
			$input['created_by'] = $input['updated_by'] = $input['assigner'] = Auth::id();
			if($task = JobTasks::create($input))
			{
				if($notification['userId'])
				{
					$notification['objectId'] = $task->id;
					$notification['objectType'] = 'Task';
					$notification['subject'] = 'New';
					$notification['body'] = $task->title;
					setNotification($notification);
				}
				return json_encode($output);
			}
		}
	}
	public function taskForm($id)
	{
		return view('jobs.taskform',['task'=>JobTasks::whereId($id)->whereAssigner(Auth::id())->first()]);
	}
	public function updateTask($id)
	{
		$input = Request::only('title','description','assignee','assigner','notes','dueDate');
		$output['success'] = 'yes';
		$task = JobTasks::whereId($id)->whereAssigner(Auth::id())->first();
		$validator = JobTasks::validation($input);
		if ($validator->fails())
		{
			return view('jobs.taskform',['task'=>$task])->withErrors($validator);
		}
		else
		{
			if(isEmail($input['assignee']))
			{
				if($assignee = getUser(['email'=>$input['assignee']]))
				{
					$input['assignee'] = $notification['userId'] = $assignee->id;
				}
			}
			else
			{
				if($assignee = getUser(['userId'=>$input['assignee']]))
				{
					$input['assignee'] = $notification['userId'] = $assignee->id;
				}
				else
				{
					$validator->errors()->add('assignee', 'Invalid assignee!');
					$output['success'] = 'no';
					$output['validator'] = $validator->messages()->toArray();
					return json_encode($output);
				}
			}
			$input['description'] = nl2br($input['description']);
			$input['notes'] = nl2br($input['notes']);
			$input['created_by'] = $input['updated_by'] = $input['assigner'] = Auth::id();
			$toLog = $task->toArray();
			$toLog['taskId']=$toLog['id'];
			unset($toLog['id']);
			unset($toLog['notes']);
			unset($toLog['deleted_at']);
			unset($toLog['assigner']);
			unset($toLog['reason']);
			JobTasksLog::insert($toLog);
			if($task->update($input))
			{
				if($notification['userId'])
				{
					$notification['objectId'] = $task->id;
					$notification['objectType'] = 'Task';
					$notification['subject'] = 'Update';
					$notification['body'] = $task->title;
					setNotification($notification);
				}
			}
			return view('followups.task',['task'=>$task]);
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
				$notification['userId'] = $task->assigner;
				$notification['objectId'] = $task->id;
				$notification['objectType'] = 'Task';
				$notification['subject'] = 'Completed';
				$notification['body'] = $task->title;
				setNotification($notification);
				$output['success'] = 'yes';
				return json_encode($output);
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
					return view('followups.task',['task'=>$task]);
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
					$notification['userId'] = $task->assignee;
					$notification['objectId'] = $task->id;
					$notification['objectType'] = 'Task';
					$notification['subject'] = 'Completion Rejected';
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
	public function cancelTask($id)
	{
			$task = JobTasks::whereId($id)->whereAssigner(Auth::id())->first();
			$output['success'] = 'no';
			if($task)
			{
				$task->status = 'Cancelled';
				if($task->save())
				{
					//return view('followups.task',['task'=>$task]);
					$output['success'] = 'yes';
				}
			}
			else
			{
				abort('403');
			}
			return json_encode($output);
	}
	public function deleteTask($id)
	{
			$task = JobTasks::whereId($id)->whereAssigner(Auth::id())->first();
			$output['success'] = 'no';
			if($task)
			{
				$task->delete();
				$notification['objectId'] = $task->id;
				$notification['objectType'] = 'Task';
				removeNotification($notification);
				$output['success'] = 'yes';
			}
			else
			{
				abort('403');
			}
			return json_encode($output);
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
				$notification['userId'] = $task->assignee;
				$notification['objectId'] = $task->id;
				$notification['objectType'] = 'Task';
				$notification['subject'] = 'Comment';
				$notification['body'] = $task->title;
				setNotification($notification);
				return view('jobs.task',['task'=>$task]);
			}
		}
		else
		{
			abort('403');
		}
	}
	public function sortbytime($sortby,$tasks)
	{
		//ref : http://www.wescutshall.com/2013/03/php-date-diff-days-negative-zero-issue/
		$mytask = array();
		if($sortby == 'timeline')
		{
			$today = new DateTime();
			foreach($tasks as $task)
			{
				if($task->status == 'Sent')
				{
					$mytask['New'][] = $task;
				}
				else
				{
					$dueDate = new DateTime($task->dueDate);
					$interval = date_diff($today, $dueDate);
					$days = $interval->format('%r%a');
					if((int)$days <= -1)
					{
						$mytask['Past deadline'][] = $task;
					}
					elseif($days  === '-0')
					{
						$mytask['Today'][] = $task;
					}
					elseif($days  === '0')
					{
						$mytask['Tomorrow'][] = $task;
					}
					elseif((int)$days <= 1)
					{
						$mytask['Day after'][] = $task;
					}
					else
					{
						if(date('W', strtotime(date('Y-m-d H:i:s')))  === date('W', strtotime($task->dueDate)))
						{
							$mytask['Rest of week'][] = $task;
						}
						else
						{
							if(date('m', strtotime(date('Y-m-d H:i:s'))) === date('m', strtotime($task->dueDate)))
							{
								$mytask['Rest of month'][] = $task;
							}
							else
							{
								$mytask['Beyond the month'][] = $task;
							}	
						}
					}
				}
			}
			return $mytask;
		}
		echo "<pre>";
		print_r($mytask); die;
		echo "</pre>";
		foreach($tasks as $task)
		{
			if($task->type == 'minute')
			{
					if($task->status == 'Completed')
					{
						$taskCompleted[] = $task;
					}
					else if($task->status == 'Open')
					{
						$mytask['New'][] = $task;
					}
					else if($task->status == 'Closed')
					{
						//echo floor((strtotime(date('Y-m-d H:i:s')) - strtotime($task->updated_at))/(60*60*24));
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
	}
}
