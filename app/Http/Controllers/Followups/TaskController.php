<?php namespace App\Http\Controllers\Followups;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\JobTaskComments;
use App\Model\Tasks;
use App\Model\MinuteTasks;
use App\Model\Meetings;
use DateTime;
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
		$sortby = Request::get('sortby','timeline');
		$days = Request::get('days','7');
		$group = Request::get('group',NULL);
		$assignee = Request::get('assignee',NULL);
		$meeting = Request::get('meeting',NULL);
		$nowsearchtxt = Request::get('nowsearchtxt',NULL);
		$historysearchtxt = Request::get('historysearchtxt',NULL);
		$historypage = Request::get('history',NULL);
		$userId = Auth::id();
		$meetingList = Meetings::select('meetings.id','meetings.title')
					->join('tasks','meetings.id','=','tasks.meetingId')
					->where('tasks.assigner','=',Auth::id())
					->lists('title','id');
		$nowtasks = $this->nowsortby();
		$historytasks = $this->historysortby();
		return view('followups.index',['sortby'=>$sortby,'nowtasks'=>$nowtasks,'historytasks'=>$historytasks,'days'=>$days,'assignee'=>$assignee,'meeting'=>$meeting,'meetingList'=>$meetingList,
					'nowsearchtxt'=>$nowsearchtxt,'historysearchtxt'=>$historysearchtxt]);
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
	public function nowsortby()
	{
		$sortby = Request::get('sortby','timeline');
		$searchtxt = Request::get('nowsearchtxt',NULL);
		$nowtasks = array();
		$query = Tasks::whereAssigner(Auth::id());
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
		elseif($sortby == 'assignee')
		{
			$tasks = $query->orderBy('tasks.assignee')->orderBy('tasks.status','DESC')->orderBy('tasks.dueDate')->get();
			foreach($tasks as $task)
			{
				$nowtasks[$task->assigneeDetail->name]['tasks'][] = $task;
				$nowtasks[$task->assigneeDetail->name]['colorClass'] = 'boxNumberBlue';

			}
		}
		if (Request::ajax())
		{
		    return view('followups.now',['nowtasks'=>$nowtasks]);
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
		$assignee = Request::get('assignee',NULL);
		$historytasks = array();
		$searchtxt = Request::get('historysearchtxt',NULL);
		$query = Tasks::whereAssigner(Auth::id());
		if($searchtxt)
		{
			$query = $query->leftJoin('meetings','tasks.meetingId','=','meetings.id')
					->where(function($qry) use ($searchtxt){
						$qry->where("meetings.title","LIKE","%$searchtxt%")
						->orWhere("tasks.title","LIKE","%$searchtxt%")
						->orWhere("tasks.description","LIKE","%$searchtxt%");
					});
		}
		if($meeting != 'all' && $meeting != 'individuals')
		{
			$query = $query->where('meetingId','=',$meeting);
		}
		else if( $meeting == 'individuals')
		{
			$query = $query->whereNull('meetingId');
		}
		if($assignee)
		{
			$query = $query->where('assignee','=',getUser(['userId'=>$assignee])->id);
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
			$historytasks['Beginning of time']['tasks'] = $query->orderBy('tasks.updated_at','DESC')->get();
		}
		if (Request::ajax())
		{
		    return view('followups.history',['historytasks'=>$historytasks]);
		}
		else
		{
			return $historytasks;
		}
	}
}
