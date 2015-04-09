<?php namespace App\Http\Controllers;
use App\Model\Minutes;
use App\Model\Minuteshistory;
use App\Model\Tasks;
use App\Model\Tasksdraft;
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
	public function index($tid=NULL)
	{
		// if($nid)
		// {
		// 	//get myTasks histroy for note id
		// 	$task = Tasks::find($tid);
		// 	return view('tasks.history',array('task'=>$task));
		// }
		// else
		// {
		$input = Request::only('sortby');
		//get all myTasks for current user
		if($input['sortby'] == 'duedate' || !($input['sortby']))
		{
			$myTasks = Tasks::where('assignee','=',Auth::user()->id)
				->where('status','!=','close')
				->orderBy('due')->paginate(15);
			return view('tasks.mytask',array('myTasks'=>$myTasks,'input'=>$input));
		}
		else if($input['sortby'] == 'assigner')
		{
			$myTasks = Tasks::where('assignee','=',Auth::user()->id)
				->where('status','!=','close')
				->orderBy('assigner')->paginate(15);
			return view('myTasks.list_assigner',array('myTasks'=>$myTasks,'input'=>$input));
		}
		else if($input['sortby'] == 'meeting')
		{
			$myTasks = Tasks::select('myTasks.*')->where('myTasks.assignee','=',Auth::user()->id)
				->where('myTasks.status','!=','close')
				->join('minutes_history','myTasks.mhid','=','minutes_history.id')
				->join('minutes','minutes_history.mid','=','minutes.id')
				->orderBy('minutes.id')->paginate(15);
			return view('myTasks.list_meeting',array('myTasks'=>$myTasks,'input'=>$input));
		}
		else
		{
			$myTasks = Tasks::where('assignee','=',Auth::user()->id)
				->where('status','!=','close')
				->orderBy('due')->paginate(15);
			return view('myTasks.list_duedate',array('myTasks'=>$myTasks,'input'=>$input));
		}

		// }
		
	}
	public function getAdd($minute)
	{
		if($minute->meeting->hasPermissoin())
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
		$input = Request::only('title', 'description','assignee','assigner','due');
		$records=array();
		for ($i=0; $i < count($input['title']); $i++)
		{
			$tempArr['title'] = trim($input['title'][$i]);
			$tempArr['description'] = trim($input['description'][$i]);
			$tempArr['assignee'] = $input['assignee'][$i];
			$tempArr['assigner'] = $input['assigner'][$i];
			$tempArr['due'] = $input['due'][$i];
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
		//return redirect('tasks/add/'.$minute->id);
		
	}
	public function postAdd($minute)
	{
		//validation has to be done
		$this->postDraft($minute);
		$input = Request::only('title', 'description','assignee','assigner','due');
		$records=array();
		for ($i=0; $i < count($input['title']); $i++)
		{ 
			$tempArr= array();
			$tempArr['title'] = trim($input['title'][$i]);
			$tempArr['description'] = trim($input['description'][$i]);
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
		if($records)
		{	
			if($minute->tasks()->saveMany($records))
			{
				$minute->update(array('lock_flag'=>'0'));
				$minute->tasks_draft()->delete();
				return redirect('/#meetings#minute'.$minute->id);
			}
		}
		else
		{
			$error = "Error DB500";
		}
		//return redirect('user/login')->with('message', $message)->with('error', $error);
	}
	public function postComment($nid)
	{
		$input = Request::only('description');
		$input['created_by'] = $input['updated_by'] = Auth::user()->id;
		$myTasks = Tasks::find($nid);
		$record = new myTaskshistory($input);
		$myTasks->myTasks_history()->save($record);
		return view('myTasks.history',array('myTasks'=>$myTasks));
	}
	public function followup()
	{

			$input = Request::only('sortby');
			//get all myTasks for current user
			if(Auth::user()->profile->role == '999')
			{
				$myTasks = Tasks::select('myTasks.*')->where('myTasks.status','!=','close')->join('minutes_history','myTasks.mhid','=','minutes_history.id')
							->join('minutes','minutes_history.mid','=','minutes.id');
			}
			else
			{
				$myTasks = Tasks::select('myTasks.*')->join('minutes_history','myTasks.mhid','=','minutes_history.id')
							->join('minutes','minutes_history.mid','=','minutes.id')
							->where('myTasks.assigner','=',Auth::user()->id)
							->orWhere(function($query){
								$query->whereRaw('FIND_IN_SET('.Auth::id().',minutes.minuters)');
							})
							->where('myTasks.status','!=','close');
			}
			if($input['sortby'] == 'duedate' || !($input['sortby']))
			{
				$myTasks = $myTasks->orderBy('myTasks.due')->paginate(15);
				return view('myTasks.list_duedate',array('myTasks'=>$myTasks,'input'=>$input));
			}
			else if($input['sortby'] == 'assignee')
			{
				$myTasks = $myTasks->orderBy('myTasks.assignee')->paginate(15);
				return view('myTasks.list_assignee',array('myTasks'=>$myTasks,'input'=>$input));
			}
			else if($input['sortby'] == 'meeting')
			{
				$myTasks = $myTasks->orderBy('minutes.id')->paginate(15);
				return view('myTasks.list_meeting',array('myTasks'=>$myTasks,'input'=>$input));
			}
			else
			{
				$myTasks = $myTasks->orderBy('myTasks.due')->paginate(15);
				return view('myTasks.list_duedate',array('myTasks'=>$myTasks,'input'=>$input));
			}
	}

	public function accept($id)
	{
		$input = Request::only('description');
		$myTasks = Tasks::where('id','=',$id)->whereRaw('FIND_IN_SET('.Auth::id().',assignee)')->first();
		if($myTasks)
		{
			$myTasks->update(['status'=>'open','updated_by'=>Auth::id()]);
			if($input['description'])
			{
				$input['created_by'] = $input['updated_by'] = Auth::user()->id;
				$record = new myTaskshistory($input);
				$myTasks->myTasks_history()->save($record);
			}
			return redirect('myTasks/'.$id);		
		}
		else
		{
			abort('403','Unauthorized access');
		}
	}
	public function reject($id)
	{
		$input = Request::only('description');
		$myTasks = Tasks::where('id','=',$id)->whereRaw('FIND_IN_SET('.Auth::id().',assignee)')->first();
		if($myTasks)
		{
			$validation = myTaskshistory::validation($input);
			if ($validation->fails())
			{
				return redirect('myTasks/'.$id)->withErrors($validation);
			}
			else
			{
				$myTasks->update(['status'=>'rejected','updated_by'=>Auth::id()]);
				$input['created_by'] = $input['updated_by'] = Auth::user()->id;
				$record = new myTaskshistory($input);
				$myTasks->myTasks_history()->save($record);
				return redirect('myTasks/'.$id);
			}
		}
		else
		{
			abort('403','Unauthorized access');
		}
	}
	public function edit($nid)
	{
		$myTasks = Tasks::find($nid);
		$users = User::whereIn('id',explode(',', $myTasks->minute_history->attendees))->lists('name','id');
		return view('myTasks.edit',['myTasks'=>$myTasks,'users'=>$users]);
	}
	public function update($nid)
	{
		$input = Request::only('title', 'description','assignee','assigner','due');
		$validation = Tasks::validation($input);
		if ($validation->fails())
		{
			return redirect('myTasks/edit/'.$nid)->withErrors($validation);
		}
		else
		{
			$input['updated_by'] = Auth::user()->id;
			$input['status'] = 'waiting';
			$myTasks = Tasks::find($nid);
			$myTasks->update($input);
			return "updated";
		}
		
	}

}
