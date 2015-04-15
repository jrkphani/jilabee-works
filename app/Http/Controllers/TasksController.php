<?php namespace App\Http\Controllers;
use App\Model\Minutes;
use App\Model\Minuteshistory;
use App\Model\Tasks;
use App\Model\Ideas;
use App\Model\Tasksdraft;
use App\Model\Comments;
use App\User;
use Auth;
use Request;
class TasksController extends Controller {
	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index($minute)
	{
		if($minute->meeting->isAttendees())
		{
			//load task for minutes 
			return view('tasks.list',array('minute'=>$minute));
		}
		else
		{
			return abort(403, 'Unauthorized action.');
		}
		
	}
	public function mytask()
	{
		$input = Request::only('sortby','search');
		//get all myTasks for current user
		$myTasks = Tasks::where('assignee','=',Auth::user()->id)
				->where('status','!=','close');
		if(strlen($input['search'])>0)
		{
			$myTasks = $myTasks ->where('title','like','%'.$input['search'].'%');
		}
		if($input['sortby'] == 'duedate')
		{
			$myTasks = $myTasks ->orderBy('due')->paginate(15);
		}
		else if($input['sortby'] == 'assigner')
		{
			$myTasks = $myTasks->orderBy('assigner')->paginate(15);
		}
		else if($input['sortby'] == 'meeting')
		{
			if(strlen($input['search'])>0)
			{
				$myTasks = Tasks::select('tasks.*')->where('tasks.assignee','=',Auth::user()->id)
				->where('tasks.status','!=','close')
				->where('tasks.title','like','%'.$input['search'].'%')
				->join('minutes','tasks.mhid','=','minutes.id')
				->join('meetings','minutes.mid','=','meetings.id')
				->orderBy('meetings.id')->paginate(15);
			}
			else
			{
				$myTasks = Tasks::select('tasks.*')->where('tasks.assignee','=',Auth::user()->id)
				->where('tasks.status','!=','close')
				->join('minutes','tasks.mhid','=','minutes.id')
				->join('meetings','minutes.mid','=','meetings.id')
				->orderBy('meetings.id')->paginate(15);
			}
		}
		else
		{
			$myTasks = $myTasks ->orderBy('due')->paginate(15);
		}
		return view('tasks.mytask',array('myTasks'=>$myTasks,'input'=>$input));
		// }
		
	}
	public function getAdd($minute)
	{
		if($minute->meeting->isMinuter())
		{
			if($minute->lock_flag == Auth::id())
			{
				$users = User::whereIn('id',explode(',', $minute->attendees))->lists('name','id');
				return view('tasks.add',['minute'=>$minute,'users'=>$users]);	
			}
			else
			{
				return abort(403, 'Invalid action.');
			}			
		}
		else
		{
			return abort(403, 'Unauthorized action.');
		}
		
	}
	public function postDraft($minute)
	{
		if($minute->meeting->isMinuter())
		{
			$input = Request::only('title', 'description','assignee','assigner','due','taskidea');
			$records=array();
			for ($i=0; $i < count($input['title']); $i++)
			{
				$tempArr['title'] = trim($input['title'][$i]);
				$tempArr['description'] = trim($input['description'][$i]);
				$tempArr['assignee'] = $input['assignee'][$i];
				$tempArr['assigner'] = $input['assigner'][$i];
				$tempArr['due'] = $input['due'][$i];
				$tempArr['taskidea'] = $input['taskidea'][$i];
				$tempArr['created_by'] = Auth::user()->id;
				if(($tempArr['title']) || ($tempArr['description']))
				$records[] = new Tasksdraft(array_filter($tempArr));
			}
			//print_r($records); die;
			if($records)
			{
				$minute->tasks_draft()->delete();
				if($minute->tasks_draft()->saveMany($records))
				{
					//return true;
				}
				else
				{
					abort('404','Insertion failed');
				}
			}
			return 'Save Successfully!';
		}
		else
		{
			return abort(403, 'Unauthorized action.');
		}
		
	}
	public function postAdd($minute)
	{
		//validation has to be done
		$this->postDraft($minute);
		$input = Request::only('title', 'description','assignee','assigner','due','taskidea');
		$records= $ideasArr = array();
		for ($i=0; $i < count($input['title']); $i++)
		{ 
			$tempArr= array();
			$tempArr['title'] = trim($input['title'][$i]);
			$tempArr['description'] = trim($input['description'][$i]);
			if($input['taskidea'][$i] == 'task')
			{
				$tempArr['assignee'] = $input['assignee'][$i];
				$tempArr['assigner'] = $input['assigner'][$i];
				$tempArr['due'] = $input['due'][$i];
				$tempArr['created_by'] = $tempArr['updated_by'] = Auth::user()->id;
				$validation = Tasks::validation($tempArr);
				if ($validation->fails())
				{
					return redirect('minute/'.$minute->id.'/tasks/add')->withErrors($validation);
				}

				if(($tempArr['title']) && ($tempArr['description']))
				$records[] = new Tasks(array_filter($tempArr));	
			}
			else
			{
				$tempArr['orginators'] = $input['assigner'][$i];
				$tempArr['created_by'] = $tempArr['updated_by'] = Auth::user()->id;
				$ideasArr[] = new Ideas(array_filter($tempArr));
			}
			
		}
		if($records )
		{	
			$minute->ideas()->saveMany($ideasArr);
			if($minute->tasks()->saveMany($records))
			{
				$minute->update(array('lock_flag'=>'0'));
				$minute->tasks_draft()->delete();
				return redirect('/#meetings#minute'.$minute->id);
			}

		}
		else
		{
			abort('Insertion failed !');
		}
		//return redirect('user/login')->with('message', $message)->with('error', $error);
	}
	
	public function followup()
	{

			$input = Request::only('sortby','search');
			//get all folloup for current user
			$followup = Tasks::select('tasks.*')->join('minutes','tasks.mhid','=','minutes.id')
						->join('meetings','minutes.mid','=','meetings.id')
						->where('tasks.assigner','=',Auth::id())
						->orWhere(function($query){
							$query->whereRaw('FIND_IN_SET('.Auth::id().',meetings.minuters)')
							->whereNull('tasks.assigner');
						})
						->where('tasks.status','!=','close');
			if(strlen($input['search'])>0)
			{
				$followup = $followup ->where('tasks.title','like','%'.$input['search'].'%');
			}

			if($input['sortby'] == 'duedate' || !($input['sortby']))
			{
				$followup = $followup->orderBy('tasks.due')->paginate(15);
			}
			else if($input['sortby'] == 'assignee')
			{
				$followup = $followup->orderBy('tasks.assignee')->paginate(15);
			}
			else if($input['sortby'] == 'meeting')
			{
				$followup = $followup->orderBy('meetings.id')->paginate(15);
			}
			else
			{
				$followup = $followup->orderBy('tasks.due')->paginate(15);
			}
			return view('tasks.followup',array('followup'=>$followup,'input'=>$input));
	}

	public function getComment($task)
	{
		return view('comments.list',array('task'=>$task));
	}
	public function postComment($task)
	{
		if($task->minute->meeting->isAttendees())
		{
			$input = Request::only('description');
			$validation = Comments::validation($input);
			if ($validation->fails())
			{
				return redirect('task/'.$task->id.'/comments')->withErrors($validation);
			}
			else
			{
				$input['created_by'] = $input['updated_by'] = Auth::user()->id;
				$record = new Comments($input);
				$task->comments()->save($record);
				return redirect('task/'.$task->id.'/comments')->withErrors($validation)->with(['message'=>'Comment posted!']);
			}
		}
		else
		{
			abort('403','Unauthorized access');
		}
	}

	public function accept($id)
	{
		$input = Request::only('description');
		$task = Tasks::where('id','=',$id)->whereRaw('FIND_IN_SET('.Auth::id().',assignee)')->first();
		if($task)
		{
			$task->update(['status'=>'open','updated_by'=>Auth::id()]);
			if($input['description'])
			{
				$input['created_by'] = $input['updated_by'] = Auth::user()->id;
				$record = new Comments($input);
				$task->comments()->save($record);
			}
			return redirect('task/'.$id.'/comments')->with(['message'=>'Task accepted!']);	
		}
		else
		{
			abort('403','Unauthorized access');
		}
	}
	public function reject($id)
	{
		$input = Request::only('description');
		$task = Tasks::where('id','=',$id)->whereRaw('FIND_IN_SET('.Auth::id().',assignee)')->first();
		if($task)
		{
			$validation = Comments::validation($input);
			if ($validation->fails())
			{
				return redirect('task/'.$id.'/comments')->withErrors($validation)->withInput()->with(['message'=>'']);
			}
			else
			{
				$task->update(['status'=>'rejected','updated_by'=>Auth::id()]);
				$input['created_by'] = $input['updated_by'] = Auth::user()->id;
				$record = new Comments($input);
				$task->comments()->save($record);
				return redirect('task/'.$id.'/comments')->with(['message'=>'Task rejected!']);
			}
		}
		else
		{
			abort('403','Unauthorized access');
		}
	}
	public function edit($task)
	{
		if($task->minute->meeting->isMinuter())
		{
			if($task->status == 'waiting' || $task->status == 'rejected')
			{
				$users = User::whereIn('id',explode(',', $task->minute->attendees))->lists('name','id');
				return view('tasks.edit',['task'=>$task,'users'=>$users]);
			}
			else
			{
				abort('Invalid Access!');
			}
		}
		else
		{
			abort('Invalid Access!');
		}
	}
	public function update($task)
	{
		if($task->minute->meeting->isMinuter())
		{
			$input = Request::only('title', 'description','assignee','assigner','due');
			$validation = Tasks::validation($input);
			if ($validation->fails())
			{
				return redirect('task/'.$task->id.'/edit')->withErrors($validation);
			}
			else
			{
				$input['updated_by'] = Auth::user()->id;
				$input['status'] = 'waiting';
				$task->update($input);
				return "updated";
			}
		}
		else
		{
			abort('Invalid Access!');
		}
		
	}
	public function close($task)
	{
		if($task->minute->meeting->isMinuter())
		{
			$input['updated_by'] = Auth::user()->id;
			$input['status'] = 'close';
			$task->update($input);
			return "updated";
		}
		else
		{
			abort('Invalid Access!');
		}
		
	}

}
