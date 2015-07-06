<?php namespace App\Http\Controllers\Jobs;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\MinuteTasks;
use Auth;
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
		return view('jobs.index');
	}
	public function mytask()
	{
		$jobtask = JobTasks::where('assignee','=',Auth::id())
					->where('status','!=','Closed')->get();
		$minutetask = MinuteTasks::where('assignee','=',Auth::id())
					->where('status','!=','Closed')->get();
		return view('jobs.mytask',['minutetask'=>$minutetask,'jobtask'=>$jobtask]);
	}
	public function viewTask($id)
	{
		$task = JobTasks::where('id','=',$id)->where('assignee','=',Auth::id())->first();
		return view('jobs.task',['task'=>$task]);
	}
	public function viewfollowup($id)
	{
		$task = JobTasks::where('id','=',$id)->where('assigner','=',Auth::id())->first();
		return view('jobs.followupTask',['task'=>$task]);
	}
	public function acceptTask($id)
	{
		$task = JobTasks::find($id)->where('status','=','Sent')->where('assignee','=',Auth::id())->first();
		$task->status = 'Open';
		$task->save();
		return view('jobs.task',['task'=>$task]);
	}
	public function rejectTask($id)
	{
		$input = Request::only('reason');
		if($input['reason'])
		{
			$task = JobTasks::find($id)->where('status','=','Sent')->where('assignee','=',Auth::id())->first();
			$task->status = 'Rejected';
			$task->reason = $input['reason'];
			$task->save();
			return view('jobs.task',['task'=>$task]);
		}
		else
		{
			$task = JobTasks::find($id);
			return view('jobs.task',['task'=>$task,'reason_err'=>'Reason for rejection is require']);
		}
	}
	public function followups()
	{
		$jobtask = JobTasks::where('assigner','=',Auth::id())
					->where('status','!=','Closed')->get();
		$minutetask = MinuteTasks::where('assigner','=',Auth::id())
					->where('status','!=','Closed')->get();
		return view('jobs.followups',['minutetask'=>$minutetask,'jobtask'=>$jobtask]);
	}
	public function history()
	{
		$history = JobTasks::where('assignee','=',Auth::id())
					->orWhere('assigner','=',Auth::id())->get();
		return view('jobs.history',['history'=>$history]);
	}
	public function createTask(Request $request)
	{
		$input = Request::only('title','description','assignee','assigner','dueDate');
		if(!$input['assignee'])
			{
				$input['assignee'] = $input['assigneeEmail'];
			}
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
			$input['created_by'] = $input['updated_by'] = $input['assigner'] = Auth::id();
			$task = JobTasks::create($input);
			//$task->tId = "T".$task->id;
			//$task->save();
			return json_encode($output);
		}
	}
}
