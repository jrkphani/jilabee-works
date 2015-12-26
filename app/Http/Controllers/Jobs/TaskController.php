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
		$nowsortby = Request::get('nowsortby','timeline');
		$historysortby = Request::get('historysortby','timeline');
		$days = Request::get('days','7');
		$group = Request::get('group',NULL);
		$nowsearchtxt = Request::get('nowsearchtxt',NULL);
		$historysearchtxt = Request::get('historysearchtxt',NULL);
		$historypage = Request::get('history',NULL);
		$nowtasks = $this->nowsortby();
		$historytasks = $this->historysortby();
		return view('jobs.index',['nowsortby'=>$nowsortby,'historysortby'=>$historysortby,'nowtasks'=>$nowtasks,'historytasks'=>$historytasks,'days'=>$days,'nowsearchtxt'=>$nowsearchtxt,'historysearchtxt'=>$historysearchtxt]);
	}
	public function newticket()
	{
		return view('jobs.ticket');
	}
	public function newticketpost()
	{
		$input = Request::only('email','issue','invoice','llr');
		$rule = array('email'=>'required',
            'issue'=>'required|max:1000',
            'invoice'=>'required|max:20',
            'llr'=>'required|max:20');
        $validator = Validator::make($input,$rule);
		if ($validator->fails())
		{
			return redirect('ticket/new')->withErrors($validator)->withInput();
		}
		else
		{
			return view('jobs.ticket');
		}
	}
	public function viewTask($id)
	{
		$task = JobTasks::whereId($id)->whereAssignee(Auth::id())->first();
		// $notification['userId'] = $task->assignee;
		// $notification['objectId'] = $task->id;
		// $notification['objectType'] = 'jobs';
		// readNotification($notification);
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
			$input['created_by'] = $input['updated_by'] = Auth::id();
			$input['description'] = 'Task Accepted';
			$comment = new JobTaskComments($input);
			$task->comments()->save($comment);
			$notification['userId'] = $task->assigner;
			$notification['objectId'] = $task->id;
			$notification['objectType'] = 'followups';
			$notification['subject'] = 'update';
			$notification['tag'] = 'now';
			$notification['body'] = 'Task #'.$task->id.' accepted';
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
				$input1['created_by'] = $input1['updated_by'] = Auth::id();
				$input1['description'] = 'Task Rejected : '.$input['reason'];
				$comment = new JobTaskComments($input1);
				$task->comments()->save($comment);
				$notification['userId'] = $task->assigner;
				$notification['objectId'] = $task->id;
				$notification['objectType'] = 'followups';
				$notification['subject'] = 'update';
				$notification['tag'] = 'now';
				$notification['body'] = 'Task #'.$task->id.' rejected';
				setNotification($notification);
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
		//disable add task for general user on followup
		if(!getOrgId())
		{
			abort('404');
		}
		$input = Request::only('title','description','assignee','assigner','clientEmail','notes','dueDate');
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
			//disable adding email in add task on followup
			// if(isEmail($input['assignee']))
			// {
			// 	if($assignee = getUser(['email'=>$input['assignee']]))
			// 	{
			// 		$input['assignee'] = $notification['userId'] = $assignee->id;
			// 	}
			// 	else
			// 	{
			// 		//mark the task as accepted for who do have an account
			// 		$input['status'] = 'Open';
			// 		$notification['userId']=0;
			// 	}
			// }
			// else
			// {
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
			//}
			$input['description'] = nl2br($input['description']);
			$input['notes'] = nl2br($input['notes']);
			$input['created_by'] = $input['updated_by'] = $input['assigner'] = Auth::id();
			if($task = JobTasks::create($input))
			{
				if($notification['userId'])
				{
					$notification['objectId'] = $task->id;
					$notification['objectType'] = 'jobs';
					$notification['subject'] = 'new';
					$notification['tag'] = 'now';
					$notification['body'] = 'Task #'.$task->id.' sent by '.Auth::user()->profile->name;
					setNotification($notification);
					sendEmail($assignee->email,$assignee->profile->name,'New Ticket','emails.newTask',['task'=>$task,'user'=>$assignee]);
					if(isEmail($input['clientEmail']))
					{
						//sendEmail($input['clientEmail'],$input['clientEmail'],'Ticket','emails.toClient',['task'=>$task,'user'=>$assignee]);
					}
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
		$input = Request::only('title','description','assignee','assigner','clientEmail','notes','dueDate');
		$output['success'] = 'yes';
		$task = JobTasks::whereId($id)->whereAssigner(Auth::id())->first();
		$validator = JobTasks::validation($input);
		if ($validator->fails())
		{
			return view('jobs.taskform',['task'=>$task])->withErrors($validator);
		}
		else
		{
			// if(isEmail($input['assignee']))
			// {
			// 	if($assignee = getUser(['email'=>$input['assignee']]))
			// 	{
			// 		$input['assignee'] = $notification['userId'] = $assignee->id;
			// 	}
			// 	else
			// 	{
			// 		$notification['userId'] = 0;
			// 	}
			// }
			// else
			// {
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
			//}
			$input['description'] = nl2br($input['description']);
			$input['notes'] = nl2br($input['notes']);
			$input['status'] = 'Sent';
			$input['created_by'] = $input['updated_by'] = $input['assigner'] = Auth::id();
			$toLog = $task->toArray();
			$toLog['taskId']=$toLog['id'];
			if(isEmail($task->assignee))
			{
				$oldAssignee = 0;
			}
			else
			{
				$oldAssignee = $task->assignee;
			}
			unset($toLog['id']);
			unset($toLog['clientEmail']);
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
					$notification['objectType'] = 'jobs';
					$notification['subject'] = 'new';
					$notification['tag'] = 'update';
					$notification['body'] = 'Task #'.$task->id.' is modified by '.Auth::user()->profile->name;
					setNotification($notification);
				}
				if(($oldAssignee) && ($oldAssignee != $notification['userId']))
				{
					$input1['created_by'] = $input1['updated_by'] = Auth::id();
					$input1['description'] = 'Task reassigned';
					$comment = new JobTaskComments($input1);
					$task->comments()->save($comment);
					$notification['userId'] = $oldAssignee;
					$notification['isRead'] = '2';
					$notification['objectId'] = $task->id;
					$notification['objectType'] = 'jobs';
					$notification['subject'] = 'reassigned';
					$notification['tag'] ='now';
					$notification['body'] = 'Task #'.$task->id.' has been reassigned by '.Auth::user()->profile->name;
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
				$input['created_by'] = $input['updated_by'] = Auth::id();
				$input['description'] = 'Task marked as completed';
				$comment = new JobTaskComments($input);
				$task->comments()->save($comment);
				$notification['userId'] = $task->assigner;
				$notification['objectId'] = $task->id;
				$notification['objectType'] = 'followups';
				$notification['subject'] = 'update';
				$notification['tag'] = 'now';
				$notification['body'] = Auth::user()->profile->name.' completed task #'.$task->id;
				setNotification($notification);
				Session::flash('message', 'Task marked as completed');
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
					$input['created_by'] = $input['updated_by'] = Auth::id();
					$input['description'] = 'Task completion accepted and closed';
					$comment = new JobTaskComments($input);
					$task->comments()->save($comment);
					$notification['userId'] = $task->assignee;
					$notification['objectId'] = $task->id;
					$notification['objectType'] = 'jobs';
					$notification['subject'] = 'update';
					$notification['tag'] = 'history';
					$notification['body'] = 'Task #'.$task->id.' completion accepted';
					setNotification($notification);
					return view('followups.task',['task'=>$task]);
					if(isEmail($task->clientEmail))
					{
						//sendEmail($task->clientEmail,$task->clientEmail,'Ticket','emails.toClient',['task'=>$task,'user'=>$assignee]);
					}
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
					$input['created_by'] = $input['updated_by'] = Auth::id();
					$input['description'] = 'Task completion rejected';
					$comment = new JobTaskComments($input);
					$task->comments()->save($comment);
					$notification['userId'] = $task->assignee;
					$notification['objectId'] = $task->id;
					$notification['objectType'] = 'jobs';
					$notification['subject'] = 'update';
					$notification['tag'] = 'now';
					$notification['body'] = 'Task #'.$task->id.' completion failed';
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
					$input['created_by'] = $input['updated_by'] = Auth::id();
					$input['description'] = 'Task cancelled';
					$comment = new JobTaskComments($input);
					$task->comments()->save($comment);
					$output['success'] = 'yes';
					$notification['userId'] = $task->assignee;
					$notification['objectId'] = $task->id;
					$notification['objectType'] = 'jobs';
					$notification['subject'] = 'update';
					$notification['tag'] = 'history';
					$notification['body'] = 'Task #'.$task->id.' has been cancelled and sent history';
					setNotification($notification);
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
				$input['created_by'] = $input['updated_by'] = Auth::id();
				$input['description'] = 'Task deleted';
				$comment = new JobTaskComments($input);
				$task->comments()->save($comment);
				$task->delete();
				$notification['userId'] = $task->assignee;
				$notification['objectId'] = $task->id;
				$notification['objectType'] = 'jobs';
				$notification['subject'] = 'removed';
				$notification['tag'] ='';
				$notification['isRead'] = '2';
				$notification['body'] = 'Task #'.$task->id.' has been removed by '.Auth::user()->profile->name;
				setNotification($notification);
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
				$notification['objectType'] = 'jobs';
				$notification['subject'] = 'comment';
				$notification['tag'] = 'now';
				$notification['body'] = 'Comment added by '.Auth::user()->profile->name.' for task #'.$task->id;
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
		$sortby = Request::get('nowsortby','timeline');
		$searchtxt = Request::get('nowsearchtxt',NULL);
		$nowtasks = array();
		$query = Tasks::select('tasks.*')->whereAssignee(Auth::id())->where('status','!=','Closed')->where('status','!=','Cancelled');
		if($searchtxt)
		{
			$query = $query->leftJoin('meetings','tasks.meetingId','=','meetings.id')
					->where(function($qry) use ($searchtxt){
						$qry->where("meetings.title","LIKE","%$searchtxt%")
						->orWhere("tasks.title","LIKE","%$searchtxt%")
						->orWhere("tasks.description","LIKE","%$searchtxt%");
					});
		}
		if($sortby == 'timeline')
		{
			$tasks = $query->orderBy('tasks.status','DESC')->orderBy('tasks.dueDate')->get();
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
			$tasks = $query->orderBy('tasks.type')->orderBy('tasks.status','DESC')->orderBy('tasks.dueDate')->get();
			foreach($tasks as $task)
			{
				if($task->type == 'minute')
				{
					$nowtasks[$task->meeting->title]['tasks'][] = $task;
					$nowtasks[$task->meeting->title]['colorClass'] = 'boxNumberBlue';
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
			$tasks = $query->orderBy('tasks.assigner')->orderBy('tasks.status','DESC')->orderBy('tasks.dueDate')->get();
			foreach($tasks as $task)
			{
				$nowtasks[$task->assignerDetail->name]['tasks'][] = $task;
				$nowtasks[$task->assignerDetail->name]['colorClass'] = 'boxNumberBlue';

			}
		}
		if (Request::ajax())
		{
		    return view('jobs.now',['nowtasks'=>$nowtasks]);
		}
		else
		{
			return $nowtasks;
		}
	}
	public function historysortby()
	{
		$days = Request::get('days','7');
		$sortby = Request::get('historysortby','timeline');
		$historytasks = array();
		$searchtxt = Request::get('historysearchtxt',NULL);
		$query = Tasks::select('tasks.*')->whereAssignee(Auth::id())->where(function($qry)
		{
			$qry->where('status','=','Closed')->orWhere('status','=','Cancelled');
		});
		
		if($searchtxt)
		{
			$query = $query->leftJoin('meetings','tasks.meetingId','=','meetings.id')
					->where(function($qry) use ($searchtxt){
						$qry->where("meetings.title","LIKE","%$searchtxt%")
						->orWhere("tasks.title","LIKE","%$searchtxt%")
						->orWhere("tasks.description","LIKE","%$searchtxt%");
					});
		}
		if(!is_array($days))
		{
			$startDate = date("Y-m-d 00:00:00",strtotime("-".$days." days"));
			$query = $query->where('tasks.updated_at','>=',$startDate);
		}
		if($sortby == 'timeline')
		{
			$tasks = $query->orderBy('tasks.updated_at','DESC')->get();
			$today = new DateTime();
			foreach($tasks as $task)
			{
				$updated_at = new DateTime($task->updated_at);
				$interval = date_diff($today, $updated_at);
				$days = $interval->format('%r%a');
				if($days  === '-0')
				{
					$historytasks['Completed Today']['tasks'][] = $task;
					$historytasks['Completed Today']['colorClass'] = 'boxNumberGreen';
				}
				else if(date('W', strtotime(date('Y-m-d H:i:s')))  === date('W', strtotime($task->updated_at)))
				{
					$historytasks['This Week']['tasks'][] = $task;
					$historytasks['This Week']['colorClass'] = 'boxNumberGreen';
				}
				else if((int)date('W', strtotime(date('Y-m-d H:i:s')))  === ((int)date('W', strtotime($task->updated_at)))+1)
				{
					$historytasks['Past Week']['tasks'][] = $task;
					$historytasks['Past Week']['colorClass'] = 'boxNumberGreen';
				}
				else if(date('mY', strtotime(date('Y-m-d H:i:s'))) === date('mY', strtotime($task->updated_at)))
				{
					$historytasks['This Month']['tasks'][] = $task;
					$historytasks['This Month']['colorClass'] = 'boxNumberBlue';							
				}
				else
				{
					$coltitle = date('M Y', strtotime($task->updated_at));
					$historytasks[$coltitle]['tasks'][] = $task;
					$historytasks[$coltitle]['colorClass'] = 'boxNumberBlue';
				}
			}
		}
		else if($sortby == 'meeting')
		{
			$tasks = $query->orderBy('tasks.type')->orderBy('tasks.updated_at','DESC')->get();
			foreach($tasks as $task)
			{
				if($task->type == 'minute')
				{
					$historytasks[$task->meeting->title]['tasks'][] = $task;
					$historytasks[$task->meeting->title]['colorClass'] = 'boxNumberBlue';
				}
				else
				{
					$historytasks['Individuals']['tasks'][] = $task;
					$historytasks['Individuals']['colorClass'] = 'boxNumberBlue';
				}

			}
		}
		elseif($sortby == 'assigner')
		{
			$tasks = $query->orderBy('tasks.assigner')->orderBy('tasks.updated_at','DESC')->get();
			foreach($tasks as $task)
			{
				$historytasks[$task->assignerDetail->name]['tasks'][] = $task;
				$historytasks[$task->assignerDetail->name]['colorClass'] = 'boxNumberBlue';

			}
		}
		if (Request::ajax())
		{
		    return view('jobs.history',['historytasks'=>$historytasks]);
		}
		else
		{
			return $historytasks;
		}
	}
	public function isReadNotification()
	{
		$notification['userId'] = Auth::id();
		$notification['parentId'] = Request::get('mid');
		$notification['objectId'] = Request::get('tid');
		$notification['objectType'] = 'jobs';
		readNotification($notification);
	}
}
