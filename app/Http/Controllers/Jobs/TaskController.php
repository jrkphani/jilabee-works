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
					->where('status','!=','closed')->get();
		$minutetask = MinuteTasks::where('assignee','=',Auth::id())
					->where('status','!=','closed')->get();
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
	public function followups()
	{
		$jobtask = JobTasks::where('assigner','=',Auth::id())
					->where('status','!=','closed')->get();
		$minutetask = MinuteTasks::where('assigner','=',Auth::id())
					->where('status','!=','closed')->get();
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
			JobTasks::create($input);
			return json_encode($output);
		}
	}
}
