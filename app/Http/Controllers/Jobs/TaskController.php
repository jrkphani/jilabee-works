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
	// public function index()
	// {
	// 	$nowsortby = Request::get('nowsortby','timeline');
	// 	$historysortby = Request::get('historysortby','timeline');
	// 	$days = Request::get('days','7');
	// 	$group = Request::get('group',NULL);
	// 	$nowsearchtxt = Request::get('nowsearchtxt',NULL);
	// 	$historysearchtxt = Request::get('historysearchtxt',NULL);
	// 	$historypage = Request::get('history',NULL);
	// 	$nowtasks = $this->nowsortby();
	// 	$historytasks = $this->historysortby();
	// 	return view('jobs.index',['nowsortby'=>$nowsortby,'historysortby'=>$historysortby,'nowtasks'=>$nowtasks,'historytasks'=>$historytasks,'days'=>$days,'nowsearchtxt'=>$nowsearchtxt,'historysearchtxt'=>$historysearchtxt]);
	// }
	public function viewTask($id)
	{
		$task = JobTasks::whereId($id)->whereAssignee(Auth::id())->first();
		// $notification['userId'] = $task->assignee;
		// $notification['objectId'] = $task->id;
		// $notification['objectType'] = 'jobs';
		// readNotification($notification);
		return view('jobs.test',['task'=>$task]);
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
		return redirect('jobs/task/'.$id);
		//return redirect('jobs/mytask');
		// $output['success'] = 'yes';
		// return json_encode($output);
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
				//return json_encode($output);
				return redirect('jobs/task/'.$id);
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
			return redirect('jobs/task/'.$id)->withErrors($validator)->withInput($input);
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
				return redirect('jobs/task/'.$id);
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
		$searchtxt = Request::get('searchtxt',NULL);
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
				if(($task->status == 'Sent') ||($task->status == 'Rejected') || ($task->type == 'minute' && $task->minute->filed != '1'))
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
		return view('jobs.now',['nowsearchtxt'=>$searchtxt,'nowsortby'=>$sortby,'nowtasks'=>$nowtasks]);
		// if (Request::ajax())
		// {
		//     return view('jobs.now',['nowtasks'=>$nowtasks]);
		// }
		// else
		// {
		// 	return $nowtasks;
		// }
	}
	public function historysortby()
	{
		$days = Request::get('days','7');
		$sortby = Request::get('sortby','timeline');
		$historytasks = array();
		$searchtxt = Request::get('searchtxt',NULL);
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
		return view('jobs.history',['nowsearchtxt'=>$searchtxt,'nowsortby'=>$sortby,'nowtasks'=>$historytasks]);
		// if (Request::ajax())
		// {
		//     return view('jobs.history',['historytasks'=>$historytasks]);
		// }
		// else
		// {
		// 	return $historytasks;
		// }
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
