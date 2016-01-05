<?php namespace App\Http\Controllers\Followups;
use App\Http\Controllers\Controller;
use Request;
use Response;
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
	public function getReport()
	{
		return view('followups.report');
	}
	public function postReport()
	{
		 $rule = array('startdate' => 'required|date',
		 	'enddate' => 'required|date|after:startdate');
		 $input = Request::only('startdate','enddate');
        $validator = Validator::make($input,$rule);
        if ($validator->fails())
		{
			return redirect('report')->withErrors($validator)->withInput();
		}
		$query = JobTasks::select('*');
		if(Auth::user()->isAdmin)
		{
			//show all task
		}
		else if(Auth::user()->profile->role == 2)
		{

			$query->where(function($q1){
				$q1->where('assigner','=',Auth::id())->orWhere('assigner','=','-1');
			});
		}
		else
		{
			$query->whereAssigner(Auth::id());
		}
		$startDate = date("Y-m-d 00:00:00",strtotime($input['startdate']));
		$endDate = date("Y-m-d 00:00:00",strtotime($input['enddate']));
		$query = $query->where(function($q2) use($startDate,$endDate){
			$q2->orWhere(function($q3) use($startDate,$endDate){
				$q3->where(function($qr1){
					$qr1->where('status','=','Open');
					$qr1->orWhere('status','=','Sent');
				});
				$q3->where(function($qr2) use($startDate,$endDate){
					$qr2->where('created_at','>=',$startDate);
					$qr2->where('created_at','<=',$endDate);
				});
			});
			$q2->orWhere(function($q4) use($startDate,$endDate){
				$q4->where('created_at','>=',$startDate);
				$q4->where('created_at','<=',$endDate);
			});
			$q2->orWhere(function($q4) use($startDate,$endDate){
				$q4->where('updated_at','>=',$startDate);
				$q4->where('updated_at','<=',$endDate);
			});
		});
		$tasks = $query->get();
		$filename = tempnam("/tmp", "report");
		$handle = fopen($filename, 'w+');
		fputcsv($handle, array('TASK ID','TITLE','DESCRIPTION','EMAIL','STATUS','ASSIGNEE','ASSIGNER','CREATED_AT','UPDATED_AT'));

		foreach($tasks as $row) {
			//echo $row->title;
			$assignee=$assigner='';
			if($row->assignee && !isEmail($row->assignee))
			{
				$assignee = $row->assigneeDetail->name.'('.$row->assigneeDetail->user->email.')';
			}
			if($row->assigner && ($row->assigner != '-1') && !isEmail($row->assigner))
			{
				$assigner = $row->assignerDetail->name.'('.$row->assignerDetail->user->email.')';
			}
		    fputcsv($handle, array($row->id,$row->title,$row->description,$row->clientEmail,$row->status,$assignee,$assigner,$row->created_at,$row->updated_at));
		}

		fclose($handle);

		$headers = array(
		    'Content-Type' => 'text/csv',
		);

		return Response::download($filename, $input['startdate'].'_'.$input['enddate'].'.csv', $headers);
	}
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
		return view('followups.index',['nowsortby'=>$nowsortby,'historysortby'=>$historysortby,'nowtasks'=>$nowtasks,'historytasks'=>$historytasks,'days'=>$days,'nowsearchtxt'=>$nowsearchtxt,'historysearchtxt'=>$historysearchtxt]);
	}
	public function newticket()
	{
		if(Auth::check())
		{
			return redirect('/jobs');
		}
		return view('jobs.ticket');
	}
	public function newticketpost()
	{
		$input = Request::only('email','issue','invoice','lrn','lrd','location','transport','g-recaptcha-response');
		$rule = array('email'=>'required',
            'issue'=>'required|max:1000',
            'invoice'=>'required|max:20',
            'lrn'=>'required|max:20',
            'lrd'=>'required|date|before:now',
            'transport'=>'required|max:20',
            //comment below line when you test on local 'g-recaptcha-response' => 'required|captcha',
            'g-recaptcha-response' => 'required|captcha',
            'location'=>'required');
        $validator = Validator::make($input,$rule);
		$validator->after(function($validator) use ($input)
		{
			$searchtxt = $input['invoice'].'/'.$input['lrn'];
			if(jobTasks::where('title','LIKE',$searchtxt.'%')->where('status','!=','Cancelled')->where('status','!=','Closed')->first())
			{
				$validator->errors()->add('invoice', 'Ticket already in open for invoice number');
				$validator->errors()->add('lrn', 'Ticket already in open for LR number');
			}
		});
		if ($validator->fails())
		{
			return redirect('ticket/new')->withErrors($validator)->withInput();
		}
		else
		{
			$data['clientEmail'] = $input['email'];
			$data['title'] = $input['invoice'].'/'.$input['lrn'].'--'.$input['lrd'].'--'.$input['location'].'--'.$input['transport'];
			$data['description'] = nl2br($input['issue']);
			$data['created_by'] = $data['updated_by'] = 1;
			$data['assigner'] = -1;
			if($task = JobTasks::create($data))
			{
				sendEmail($input['email'],$input['email'],'Ticket','emails.toClient',['task'=>$task,'state'=>'new']);
				return view('jobs.ticketSuccess',['task'=>$task]);
			}
		}
	}
	public function viewTicket()
	{
		return view('jobs.viewTicketForm',['error'=>'']);
	}
	public function viewTicketPost()
	{
		$input = Request::only('email','ticket');
		$task = JobTasks::whereId($input['ticket'])->where('clientEmail',$input['email'])->first();
		if($task)
		{
			return view('jobs.viewTicket',['task'=>$task]);
		}
		else
		{
			return view('jobs.viewTicketForm',['error'=>'Invalid email and ticket number']);
		}
	}
	public function viewTask($id)
	{
		if(Auth::user()->profile->role == '2')
		{
			$task = JobTasks::whereId($id)->where(function($q){
				$q->where('assigner','=',Auth::id())->orWhere('assigner','=','-1');
			})->first();
		}
		else
		{
			$task = JobTasks::whereId($id)->whereAssigner(Auth::id())->first();
		}
		// $notification['userId'] = $task->assigner;
  //       $notification['objectId'] = $task->id;
  //       $notification['objectType'] = 'followups';
  //       readNotification($notification);
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
				$notification['objectType'] = 'jobs';
				$notification['subject'] = 'comment';
				$notification['tag'] = 'now';
				$notification['body'] = 'Comment added by '.Auth::user()->profile->name.' for task #'.$task->id;
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
				$notification['objectType'] = 'jobs';
				$notification['subject'] = 'comment';
				$notification['tag'] = 'now';
				$notification['body'] = 'Comment added by '.Auth::user()->profile->name.' for task #'.$task->id;
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
		$input = Request::only('title','description','assignee','clientEmail','notes','dueDate');
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
		$query = Tasks::select('tasks.*')->where('status','!=','Closed')->where('status','!=','Cancelled');
		if(Auth::user()->profile->role == 2)
		{

			$query->where(function($q){
				$q->where('assigner','=',Auth::id())->orWhere('assigner','=','-1');
			});
		}
		else
		{
			$query->whereAssigner(Auth::id());
		}
		if($searchtxt)
		{
			$query = $query->leftJoin('meetings','tasks.meetingId','=','meetings.id')
					->where(function($qry) use ($searchtxt){
						$qry->where("meetings.title","LIKE","%$searchtxt%")
						->orWhere("tasks.title","LIKE","%$searchtxt%")
						->orWhere("tasks.description","LIKE","%$searchtxt%");
					});
			$draft = JobDraft::where('assigner','=',Auth::id())
					->where(function($qry)  use ($searchtxt){
						$qry->where("title","LIKE","%$searchtxt%")
						->orWhere("description","LIKE","%$searchtxt%");
					})
					->orderBy('updated_at','desc')->get();
			if(count($draft))
			{
				$nowtasks['Draft']['tasks'] = $draft;
				$nowtasks['Draft']['colorClass'] = 'boxNumberRed';
			}
		}
		else
		{
			$draft = JobDraft::where('assigner','=',Auth::id())->orderBy('updated_at','desc')->get();
			if(count($draft))
			{
				$nowtasks['Draft']['tasks'] = $draft;
				$nowtasks['Draft']['colorClass'] = 'boxNumberRed';
			}
		}
		if($sortby == 'timeline')
		{
			$tasks = $query->orderBy('tasks.status','DESC')->orderBy('tasks.dueDate')->get();
			$today = new DateTime();
			foreach($tasks as $task)
			{
				if(!$task->assignee)
				{
					$nowtasks['Unassigned ']['tasks'][] = $task;
					$nowtasks['Unassigned ']['colorClass'] = 'boxNumberBlue';
				}
				else if(($task->status == 'Sent') || ($task->type == 'minute' && $task->minute->filed != '1'))
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
		// elseif($sortby == 'meeting')
		// {
		// 	$tasks = $query->orderBy('tasks.type')->orderBy('tasks.status','DESC')->orderBy('tasks.dueDate')->get();
		// 	foreach($tasks as $task)
		// 	{
		// 		if($task->type == 'minute')
		// 		{
		// 			$nowtasks[$task->meeting->title]['tasks'][] = $task;
		// 			$nowtasks[$task->meeting->title]['colorClass'] = 'boxNumberBlue';
		// 		}
		// 		else
		// 		{
		// 			$nowtasks['Individuals']['tasks'][] = $task;
		// 			$nowtasks['Individuals']['colorClass'] = 'boxNumberBlue';
		// 		}

		// 	}
		// }
		elseif($sortby == 'assignee')
		{
			$tasks = $query->orderBy('tasks.assignee')->orderBy('tasks.status','DESC')->orderBy('tasks.dueDate')->get();
			foreach($tasks as $task)
			{
				if(!$task->assignee)
				{
					$nowtasks['Unassigned ']['tasks'][] = $task;
					$nowtasks['Unassigned ']['colorClass'] = 'boxNumberBlue';
				}
				else if(isEmail($task->assignee))
				{
					$nowtasks[$task->assignee]['tasks'][] = $task;
					$nowtasks[$task->assignee]['colorClass'] = 'boxNumberBlue';
				}
				else
				{
					$nowtasks[$task->assigneeDetail->name]['tasks'][] = $task;
					$nowtasks[$task->assigneeDetail->name]['colorClass'] = 'boxNumberBlue';
				}

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
		$days = Request::get('days','7');
		$sortby = Request::get('historysortby','timeline');
		$historytasks = array();
		$searchtxt = Request::get('historysearchtxt',NULL);
		$query = Tasks::select('tasks.*')->where(function($qry)
		{
			$qry->where('status','=','Closed')->orWhere('status','=','Cancelled');
		});
		if(Auth::user()->profile->role == 2)
		{

			$query->where(function($q){
				$q->where('assigner','=',Auth::id())->orWhere('assigner','=','-1');
			});
		}
		else
		{
			$query->whereAssigner(Auth::id());
		}
		if($searchtxt)
		{
			$query = $query->where(function($qry) use ($searchtxt){
						//$qry->where("meetings.title","LIKE","%$searchtxt%");
						$qry->Where("tasks.title","LIKE","%$searchtxt%")
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
				//echo $task->updated_at."<br>";
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
		// else if($sortby == 'meeting')
		// {
		// 	$tasks = $query->orderBy('tasks.type')->orderBy('tasks.updated_at','DESC')->get();
		// 	foreach($tasks as $task)
		// 	{
		// 		if($task->type == 'minute')
		// 		{
		// 			$historytasks[$task->meeting->title]['tasks'][] = $task;
		// 			$historytasks[$task->meeting->title]['colorClass'] = 'boxNumberBlue';
		// 		}
		// 		else
		// 		{
		// 			$historytasks['Individuals']['tasks'][] = $task;
		// 			$historytasks['Individuals']['colorClass'] = 'boxNumberBlue';
		// 		}

		// 	}
		// }
		elseif($sortby == 'assignee')
		{
			$tasks = $query->orderBy('tasks.assignee')->orderBy('tasks.dueDate')->get();
			foreach($tasks as $task)
			{
				$historytasks[$task->assigneeDetail->name]['tasks'][] = $task;
				$historytasks[$task->assigneeDetail->name]['colorClass'] = 'boxNumberBlue';

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
		$notification['objectType'] = 'followups';
		readNotification($notification);
	}
}
