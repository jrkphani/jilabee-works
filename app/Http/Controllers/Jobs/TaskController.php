<?php namespace App\Http\Controllers\Jobs;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\JobTasksLog;
use App\Model\JobTaskComments;
use App\Model\Tasks;
use App\Model\MinuteTasks;
use App\Model\Minutes;
use App\Model\Meetings;
use App\Model\Profile;
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
		//print_r(Request::all()); die;
		//$sortby = Request::get('sortby','timeline');
		//$sortby = Request::get('sortby','meeting');
		$sortby = Request::get('sortby','timeline');
		$days = Request::get('days','7');
		$group = Request::get('group',NULL);
		$assigner = Request::get('assigner',NULL);
		$meeting = Request::get('meeting',NULL);
		$userId = Auth::id();
		$meetingList = Meetings::select('meetings.id','meetings.title')
					->join('organizations','meetings.oid','=','organizations.id')
					->where('organizations.customerId','=',getOrgId())
					->lists('title','id');
					//print_r($meetingList); die;
		//$mytask = array();
		// if($sortby == 'timeline')
		// {
		// 	$tasks = Tasks::whereAssignee(Auth::id())->orderBy('dueDate')->get();
		// }
		// elseif ($sortby == 'meeting') 
		// {
		// 	$tasks = Tasks::whereAssignee(Auth::id())->orderBy('minuteId')->get();
		// }
		// elseif ($sortby == 'assigner')
		// {
		// 	$tasks = Tasks::whereAssignee(Auth::id())->orderBy('assigner')->get();
		// }
		$nowtasks = $this->nowsortby();
		$historytasks = $this->historysortby();
		//print_r($historytask); die;
		return view('jobs.index',['sortby'=>$sortby,'nowtasks'=>$nowtasks,'historytasks'=>$historytasks,'days'=>$days,'assigner'=>$assigner,'meeting'=>$meeting,'meetingList'=>$meetingList]);
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
	public function nowsortby()
	{
		//ref : http://www.wescutshall.com/2013/03/php-date-diff-days-negative-zero-issue/
		$sortby = Request::get('sortby','timeline');
		$nowtasks = array();
		$query = Tasks::whereAssignee(Auth::id());
		if($sortby == 'timeline')
		{
			$tasks = $query->orderBy('status','DESC')->orderBy('dueDate')->get();
			$today = new DateTime();
			foreach($tasks as $task)
			{
				if(($task->status == 'Sent') || ($task->type == 'minute' && $task->minute->filed != '1'))
				{
					$nowtasks['New']['tasks'][] = $task;
					$nowtasks['New']['colorClass'] = 'boxNumberGrey';
				}
				else
				{
					$dueDate = new DateTime($task->dueDate);
					$interval = date_diff($today, $dueDate);
					$days = $interval->format('%r%a');
					if((int)$days <= -1)
					{
						$nowtasks['Past deadline']['tasks'][] = $task;
						$nowtasks['Past deadline']['colorClass'] = 'boxNumberRed';
					}
					elseif($days  === '-0')
					{
						$nowtasks['Today']['tasks'][] = $task;
						$nowtasks['Today']['colorClass'] = 'boxNumberGreen';
					}
					elseif($days  === '0')
					{
						$nowtasks['Tomorrow']['tasks'][] = $task;
						$nowtasks['Tomorrow']['colorClass'] = 'boxNumberGreen';
					}
					elseif((int)$days <= 1)
					{
						$nowtasks['Day after']['tasks'][] = $task;
						$nowtasks['Day after']['colorClass'] = 'boxNumberGreen';
					}
					else
					{
						if(date('W', strtotime(date('Y-m-d H:i:s')))  === date('W', strtotime($task->dueDate)))
						{
							$nowtasks['Rest of week']['tasks'][] = $task;
							$nowtasks['Rest of week']['colorClass'] = 'boxNumberGreen';
						}
						else
						{
							if(date('m', strtotime(date('Y-m-d H:i:s'))) === date('m', strtotime($task->dueDate)))
							{
								$nowtasks['Rest of month']['tasks'][] = $task;
								$nowtasks['Rest of month']['colorClass'] = 'boxNumberBlue';
							}
							else
							{
								$nowtasks['Beyond the month']['tasks'][] = $task;
								$nowtasks['Beyond the month']['colorClass'] = 'boxNumberBlue';
							}	
						}
					}
				}
			}
		}
		elseif($sortby == 'meeting')
		{
			$tasks = $query->orderBy('type')->orderBy('status','DESC')->orderBy('dueDate')->get();
			foreach($tasks as $task)
			{
				if($task->type == 'minute')
				{
					$nowtasks[$task->minute->meeting->title]['tasks'][] = $task;
					$nowtasks[$task->minute->meeting->title]['colorClass'] = 'boxNumberBlue';
				}
				else
				{
					$nowtasks['Individuals']['tasks'][] = $task;
					$nowtasks['Individuals']['colorClass'] = 'boxNumberBlue';
				}

			}
		}
		elseif($sortby == 'assigner')
		{
			$tasks = $query->orderBy('assigner')->orderBy('status','DESC')->orderBy('dueDate')->get();
			foreach($tasks as $task)
			{
				$nowtasks[$task->assignerDetail->name]['tasks'][] = $task;
				$nowtasks[$task->assignerDetail->name]['colorClass'] = 'boxNumberBlue';

			}
		}
		if (Request::ajax())
		{
		    return view('jobs.now',['sortby'=>$sortby,'nowtasks'=>$nowtasks]);
		}
		else
		{
			return $nowtasks;
		}
	}
	public function historysortby()
	{
		//ref : http://www.wescutshall.com/2013/03/php-date-diff-days-negative-zero-issue/
		$days = Request::get('days','7');
		$meeting = Request::get('meeting','all');
		$assigner = Request::get('assigner',NULL);
		$historytasks = array();
		$query = Tasks::whereAssignee(Auth::id());
		if($meeting != 'all')
		{
			$query = $query->where('meetingId','=',$meeting);
		}
		if($assigner)
		{
			$query = $query->where('assigner','=',getUser(['userId'=>$assigner])->id);
		}
		if($days != 'all')
		{
			$startDate = date("Y-m-d 00:00:00",strtotime("-".$days." days"));
			$tasks = $query->where('tasks.updated_at','>=',$startDate)->orderBy('tasks.updated_at','DESC')->get();
			foreach ($tasks as $task)
			{
				if(date('Y-m-d', strtotime($task->updated_at)) === date('Y-m-d'))
				{
					$historytasks['Completed Today']['tasks'][] = $task;
				}
				else
				{
					$historytasks['Last '.$days.' days']['tasks'][] = $task;	
				}
			}
		}
		else
		{

		}
		if (Request::ajax())
		{
		    return view('jobs.history',['days'=>$days,'historytasks'=>$historytasks]);
		}
		else
		{
			return $historytasks;
		}
	}
}
