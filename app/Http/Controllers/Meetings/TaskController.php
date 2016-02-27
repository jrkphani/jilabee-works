<?php namespace App\Http\Controllers\Meetings;
use App\Http\Controllers\Controller;
use Request;
use App\Model\MinuteTasks;
use App\Model\Minutes;
use App\Model\Ideas;
use App\Model\MinuteTaskComments;
use Auth;
use Activity;
use App\Model\FiledMinutes;
use DB;
use Validator;
use Session;
use PDF;
use File;
class TaskController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	/*public function __construct()
	{
	}*/
	public function createTask($mid)
	{
		$input = Request::only('tid','title','description','assignee','assigner','orginator','dueDate','type');
		$output['success'] = 'yes';
		$records= $ideasArr = array();
		$updatedFlag = 0;
		$minute = Minutes::whereId($mid)->whereFiled('0')->where('created_by','=',Auth::id())->first();
		if($minute)
		{
			//make sure only minute which is not filed can be edited
			foreach (Request::get('removeTask',array()) as $taskId)
			{
				//remove the task while the minute is not filed yet
				$task = MinuteTasks::find($taskId);
				$task->comments()->forceDelete();
				$task->forceDelete();

				$notification['parentId'] = $minute->minuteId;
				$notification['objectId'] = $taskId;
				$notification['objectType'] = 'jobs';
				$notification['subject'] = 'removed';
				$notification['tag'] ='';
				$notification['isRead'] = '2';
				$notification['body'] = 'Task #'.$taskId.' has been removed by '.Auth::user()->profile->name;
				setNotification($notification);
			}
			foreach (Request::get('removeIdea',array()) as $ideaId)
			{
				//remove the idea while the minute is not filed yet
				$idea = Ideas::find($ideaId);
				$idea->forceDelete();
			}
			for ($i=0; $i < count($input['title']); $i++)
			{
				$tempArr= $notification = array();
				$tempArr['title'] = trim($input['title'][$i]);
				$tempArr['description'] = nl2br(trim($input['description'][$i]));
				if(($tempArr['title']) && ($tempArr['description']))
				{
					if($input['type'][$i] == 'task')
					{
						$tempArr['assignee'] = $input['assignee'][$i];
						$tempArr['assigner'] = Auth::id();
						$tempArr['dueDate'] = $input['dueDate'][$i];
						$tempArr['updated_by'] = Auth::id();
						$tempArr['status'] = 'Sent';
						if($input['tid'][$i])
						{
							$oldTask = MinuteTasks::whereId($input['tid'][$i]);
							$tempArr['startDate'] = $oldTask->first()->minute->startDate;
						}
						else
						{
							$tempArr['startDate'] = $minute->startDate;
						}
						$validator = MinuteTasks::validation($tempArr);
						if ($validator->fails())
						{
							$output['success'] = 'no';
							$output['validator'] = $validator->messages()->toArray();
							return json_encode($output);
						}
						unset($tempArr['startDate']);
						if($assignee = getUser(['email'=>$input['assignee'][$i]]))
						{
							//check user has an account
							$tempArr['assignee'] = $notification['userId'] = $assignee->id;
						}
						else if(isEmail($input['assignee'][$i]))
						{
							//mark the task as accepted for who do have an account
							$tempArr['assignee'] = $input['assignee'][$i];
							$tempArr['status'] = 'Open';
							$notification['userId']=0;
						}
						else if($assignee = getUser(['userId'=>$input['assignee'][$i]]))
						{
							$tempArr['assignee'] = $notification['userId'] = $assignee->id;
						}
						if($input['tid'][$i])
						{
							$findstate = 'status'.$input['tid'][$i];
							if(Request::get($findstate,null))
							{
								if(isEmail($input['assignee'][$i]))
								{
									$tempArr['status'] = 'Open';
								}
								else
								{
									$tempArr['status'] = Request::get($findstate);
								}
							}
							$updatedFlag = 1;
							
							//if(($notification['userId']) && ($oldTask->first()->minuteId != $mid))
							if($notification['userId'])
							{
								$notification['parentId'] = $oldTask->first()->minuteId;
								$notification['objectId'] = $input['tid'][$i];
								$notification['objectType'] = 'jobs';
								$notification['subject'] = 'update';
								$notification['tag'] ='now';
								$notification['body'] = 'Task #'.$input['tid'][$i].' is modified by '.Auth::user()->profile->name;
								if($oldTask->first()->assignee != $notification['userId'])
								{
									$notification['body'] = 'Task #'.$input['tid'][$i].' sent by '.Auth::user()->profile->name;
								}
								setNotification($notification);
							}
							if($oldTask->first()->assignee != $notification['userId'])
							{
								$input1['created_by'] = $input1['updated_by'] = Auth::id();
								$input1['description'] = 'Task reassigned';
								$comment = new MinuteTaskComments($input1);
								$oldTask->first()->comments()->save($comment);
								$notification['userId'] = $oldTask->first()->assignee;
								$notification['isRead'] = '2';
								$notification['parentId'] = $oldTask->first()->minuteId;
								$notification['objectId'] = $input['tid'][$i];
								$notification['objectType'] = 'jobs';
								$notification['subject'] = 'reassigned';
								$notification['tag'] ='now';
								$notification['body'] = 'Task #'.$input['tid'][$i].' has been reassigned by '.Auth::user()->profile->name;
								setNotification($notification);
							}
							
							if($oldTask->update($tempArr))
							{
								if($oldTask->first()->status == 'Closed' || $oldTask->first()->status == 'Cancelled')
								{
									if($oldTask->first()->status == 'Closed')
									{
										$input1['description'] = 'Task closed';
									}
									else
									{
										$input1['description'] = 'Task cancelled';
									}
									$input1['created_by'] = $input1['updated_by'] = Auth::id();
									$comment = new MinuteTaskComments($input1);
									$oldTask->first()->comments()->save($comment);
								}
							}
						}
						else
						{
							$tempArr['created_by'] = Auth::id();
							$records[] = new MinuteTasks(array_filter($tempArr));
						}	
					}
					elseif($input['type'][$i] == 'idea')
					{
						if($orginator = getUser(['email'=>$input['orginator'][$i]]))
						{
							//check user has an account
							$tempArr['orginator'] = $orginator->id;
						}
						else if(isEmail($input['orginator'][$i]))
						{
							$tempArr['orginator'] = $input['orginator'][$i];
						}
						else if($orginator = getUser(['userId'=>$input['orginator'][$i]]))
						{
							$tempArr['orginator'] = $orginator->id;
						}
						$tempArr['updated_by'] = Auth::id();
						$validator = Ideas::validation($tempArr);
						if ($validator->fails())
						{
							$output['success'] = 'no';
							$output['validator'] = $validator->messages()->toArray();
							return json_encode($output);
						}
						if($input['tid'][$i])
						{
							$updatedFlag = 1;
							Ideas::whereId($input['tid'][$i])->update($tempArr);
						}
						else
						{
							$tempArr['created_by'] = Auth::id();
							$ideasArr[] = new Ideas(array_filter($tempArr));
						}
					}
				}
				
			}
			if($records || $ideasArr)
			{	
				if($ideasArr)
				{
					DB::transaction(function() use ($minute,$ideasArr)
					{
						$minute->ideas()->saveMany($ideasArr);
					});
				}
				if($records)
				{
					DB::transaction(function() use ($minute,$records)
					{
						$minute->tasks()->saveMany($records);
						foreach ($minute->tasks as $task) {
							if(!isEmail($task->assignee))
							{
								$notification['userId'] = $task->assignee;
								$notification['parentId'] = $task->minuteId;
								$notification['objectId'] = $task->id;
								$notification['objectType'] = 'jobs';
								$notification['subject'] = 'new';
								$notification['tag'] ='now';
								$notification['body'] = 'Task #'.$task->id.' sent by '.Auth::user()->profile->name;
								setNotification($notification);
							}
						}
					});
				}
				$minute->draft()->delete();
				if(!$minute->endDate)
				{
					//start date + 1 Hr
					$minute->endDate = date("d-m-Y H:i:s", strtotime($minute->startDate)+3600);
					$minute->save();
				}
				$output['meetingId'] = $mid;
				//file minutes if no new task
				//$this->fileMinute($minute->meetingId);
				return json_encode($output);

			}
			else
			{
				if($updatedFlag == 1)
				{
					//file minutes if no new task
					$this->fileMinute($minute->meetingId);
					$output['success'] = 'yes';
					$output['meetingId'] = $mid;
				}
				else
				{
					$output['success'] = 'no';
					$output['message'] = 'Empty fields';
				}
				return json_encode($output);
			}
		}
		else
		{
			abort('403');
		}	
	}
	public function viewTask($mid,$id)
	{
		$task = MinuteTasks::whereIdAndAssignee($id,Auth::id())->where('minuteId',$mid)->first();

		// $notification['userId'] = $task->assignee;
		// $notification['objectId'] = $task->id;
		// $notification['objectType'] = 'jobs';
		// readNotification($notification);
		return view('jobs.task',['task'=>$task]);
	}
	public function taskForm($mid,$id)
	{
		return view('jobs.taskform',['task'=>MinuteTasks::whereId($id)->where('minuteId',$mid)
			->whereAssigner(Auth::id())->first()]);
	}
	public function viewHistory($mid,$id)
	{
		$task = MinuteTasks::whereId($id)->where('minuteId',$mid)->where(function($query)
			{
				$query->whereAssignerOrAssignee(Auth::id(),Auth::id());
			})->first();
		return view('jobs.historyTask',['task'=>$task]);
	}
	public function acceptTask($mid,$id)
	{
		$task = MinuteTasks::whereId($id)->whereAssignee(Auth::id())->where('minuteId',$mid)
		->where(function($query)
			{
				$query->whereStatus('Sent')->orWhere('status','=','Rejected');

			})->first();
		$task->status = 'Open';
		$task->updated_by = Auth::id();
		if($task->save())
		{
			$input['created_by'] = $input['updated_by'] = Auth::id();
			$input['description'] = 'Task Accepted';
			$comment = new MinuteTaskComments($input);
			$task->comments()->save($comment);
			$this->fileMinute($task->minute->meetingId);
			$notification['userId'] = $task->assigner;
			$notification['parentId'] = $task->minuteId;
			$notification['objectId'] = $task->id;
			$notification['objectType'] = 'followups';
			$notification['subject'] = 'update';
			$notification['tag'] ='now';
			$notification['body'] = 'Task #'.$task->id.' accepted';
			setNotification($notification);
			// $output['success'] = 'yes';
			// return json_encode($output);
			return redirect('minute/'.$mid.'/task/'.$id);
		}
	}
	public function rejectTask($mid,$id)
	{
		$input = Request::only('reason');
		$output['success'] = 'no';
		if($input['reason'])
		{
			$task = MinuteTasks::whereId($id)->where('status','=','Sent')->where('minuteId',$mid)
			->where('assignee','=',Auth::id())->first();
			$task->status = 'Rejected';
			$task->reason = $input['reason'];
			$task->updated_by = Auth::id();
			if($task->save())
			{
				$input1['created_by'] = $input1['updated_by'] = Auth::id();
				$input1['description'] = 'Task Rejected : '.$input['reason'];
				$comment = new MinuteTaskComments($input1);
				$task->comments()->save($comment);
				$notification['userId'] = $task->assigner;
				$notification['parentId'] = $task->minuteId;
				$notification['objectId'] = $task->id;
				$notification['objectType'] = 'followups';
				$notification['subject'] = 'update';
				$notification['tag'] ='now';
				$notification['body'] = 'Task #'.$task->id.' rejected';
				setNotification($notification);
				$output['success'] = 'yes';
			}
			
		}
		else
		{
			$output['msg'] = 'Reason required';
			
		}
		return json_encode($output);
	}
	
	public function markComplete($mid,$id)
	{
		$task = MinuteTasks::whereIdAndAssigneeAndStatus($id,Auth::id(),'Open')->where('minuteId',$mid)->first();
		if($task)
		{
			$task->status = 'Complete';
			if($task->save())
			{
				$input['created_by'] = $input['updated_by'] = Auth::id();
				$input['description'] = 'Task marked as completed';
				$comment = new MinuteTaskComments($input);
				$task->comments()->save($comment);
				$notification['userId'] = $task->assigner;
				$notification['objectId'] = $task->id;
				$notification['objectType'] = 'followups';
				$notification['subject'] = 'update';
				$notification['tag'] ='now';
				$notification['body'] = Auth::user()->profile->name.' completed task #'.$task->id;
				setNotification($notification);
				Session::flash('message', 'Task marked as completed');
				//$output['success'] = 'yes';
				//return json_encode($output);
				return redirect('minute/'.$mid.'/task/'.$id);
			}
		}
		else
		{
			abort('403');
		}
	}
	public function acceptCompletion($mid,$id)
	{
			$task = MinuteTasks::whereIdAndAssigner($id,Auth::id())->where('minuteId',$mid)->first();
			if($task)
			{
				$task->status = 'Completed';
				if($task->save())
				{
					$input['created_by'] = $input['updated_by'] = Auth::id();
					$input['description'] = 'Task completion accepted';
					$comment = new MinuteTaskComments($input);
					$task->comments()->save($comment);
					$notification['userId'] = $task->assignee;
					$notification['objectId'] = $task->id;
					$notification['objectType'] = 'jobs';
					$notification['subject'] = 'update';
					$notification['body'] = 'Task #'.$task->id.' completion accepted';
					//return view('followups.task',['task'=>$task]);
					return redirect('minute/'.$mid.'/task/'.$id);
				}
			}
			else
			{
				abort('403');
			}
	}
	public function rejectCompletion($mid,$id)
	{
			$task = MinuteTasks::whereIdAndAssigner($id,Auth::id())->where('minuteId',$mid)->first();
			if($task)
			{
				$task->status = 'Open';
				if($task->save())
				{
					$input['created_by'] = $input['updated_by'] = Auth::id();
					$input['description'] = 'Task completion rejected';
					$comment = new MinuteTaskComments($input);
					$task->comments()->save($comment);
					$notification['userId'] = $task->assignee;
					$notification['objectId'] = $task->id;
					$notification['objectType'] = 'jobs';
					$notification['subject'] = 'update';
					$notification['tag'] ='now';
					$notification['body'] = 'Task #'.$task->id.' completion failed';
					setNotification($notification);
					//return view('followups.task',['task'=>$task]);
					return redirect('minute/'.$mid.'/task/'.$id);
				}
			}
			else
			{
				abort('403');
			}
	}
	public function taskComment($mid,$id)
	{
		$input = Request::only('description');
		$validator = MinuteTaskComments::validation($input);
		$task = MinuteTasks::whereIdAndAssignee($id,Auth::id())->where('minuteId',$mid)->first();
		if ($validator->fails())
		{
			return redirect('minute/'.$mid.'/task/'.$id)->withErrors($validator)->withInput($input);
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
				$notification['tag'] ='now';
				$notification['body'] = 'Comment added by '.Auth::user()->profile->name.' for task #'.$task->id;
				setNotification($notification);
				return redirect('minute/'.$mid.'/task/'.$id);
			}
		}
		else
		{
			abort('403');
		}
	}
	public function fileMinute($meetingId)
	{
		//file meeting along with non closed task form previous minutes too.
			$notAccepted = Minutes::select('minuteTasks.id')->where('minutes.meetingId',$meetingId)
							->join('minuteTasks','minuteTasks.minuteId','=','minutes.id')
							->where(function($query)
							{
								$query->where('minuteTasks.status','=','Sent')
								->orWhere('minuteTasks.status','=','Rejected');
							})
							->count();
							//echo $notAccepted; die;
			if(!$notAccepted)
			{
				$currentMinute = Minutes::where('meetingId',$meetingId)->where('filed','0')->first();
				//print_r($currentMinute->id); die;
				$lastFiledMinute = Minutes::where('meetingId',$meetingId)->where('filed','=','1')->orderBy('startDate', 'DESC')->limit(1)->first();
				$datenow = date('Y-m-d H:i:s');
				if($lastFiledMinute)
				{
					$tasks = Minutes::select(DB::raw("concat($currentMinute->id,'','') as minuteId , concat('$datenow','','') as created_at"),
								'minuteTasks.id as taskId','minuteTasks.title','minuteTasks.description','minuteTasks.assignee','minuteTasks.assigner','minuteTasks.status','minuteTasks.dueDate')
							//->where('minutes.meetingId',$meetingId)
							->where('minutes.id',$currentMinute->id)
							->join('minuteTasks','minuteTasks.minuteId','=','minutes.id')
							->orWhereIn('minuteTasks.id',function($query) use ($lastFiledMinute){
								$query->select('taskId')
		                    		->from('filedMinutes')
		                       		->where('filedMinutes.status','!=','Closed')
		                       		->where('filedMinutes.status','!=','Cancelled')
		                       		->where('filedMinutes.minuteId','=',$lastFiledMinute->id);
							})
							->get()->toArray();
				}
				else
				{
					$tasks = Minutes::select(DB::raw("concat($currentMinute->id,'','') as minuteId , concat('$datenow','','') as created_at"),
						'minuteTasks.id as taskId','minuteTasks.title','minuteTasks.description','minuteTasks.assignee','minuteTasks.assigner','minuteTasks.status','minuteTasks.dueDate')
							//->where('minutes.meetingId',$meetingId)
							->where('minutes.id',$currentMinute->id)
							->join('minuteTasks','minuteTasks.minuteId','=','minutes.id')
							->get()->toArray();	
				}
				if(FiledMinutes::insert($tasks))
				{
					$currentMinute->filed='1';
					$currentMinute->save();
					$meeting = $currentMinute->meeting;
					$attach = '../filedMinutes/minute'.$currentMinute->id.'.pdf';
					if (!File::exists($attach))
					{
						$pdf = PDF::loadView('meetings.pdfFile',['minute'=>$currentMinute]);
						$pdf->setWarnings(false)->save($attach);
					}
					foreach (explode(',',$currentMinute->attendees) as $key => $value)
					{
						if(isEmail($value))
						{
							sendEmail($value,$value,'Jotter Account','emails.filedMinutes',['user'=>NULL,'meeting'=>$meeting],$attach);
						}
						else
						{
							$user = getUser(['id'=>$value]);
							sendEmail($user->email,$user->profile->name,'Jotter Account','emails.filedMinutes',['user'=>NULL,'meeting'=>$meeting],$attach);
						}
					}
				}
			}
	}
}
