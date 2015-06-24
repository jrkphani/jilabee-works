<?php namespace App\Http\Controllers\Jobs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\JobTasks;
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
		$mytask = JobTasks::where('assignee','=',Auth::id())
					->where('status','!=','close')->get();
		/*$jotask = JobTasks::where('assignee','=',Auth::id())
					->where('status','!=','close')->get();
		$meetingtask = JobTasks::where('assignee','=',Auth::id())
					->where('status','!=','close')->get();
		$users = DB::table('users')->whereNull('last_name')->union($first)->get();*/
		return view('jobs.mytask',['mytask'=>$mytask]);
	}
	public function followups()
	{
		$followups = JobTasks::where('assigner','=',Auth::id())
					->where('status','!=','close')->get();
		return view('jobs.followups',['followups'=>$followups]);
	}
	public function history()
	{
		$history = JobTasks::where('assignee','=',Auth::id())
					->orWhere('assigner','=',Auth::id())->get();
		return view('jobs.history',['history'=>$history]);
	}
	public function createTask(Request $request)
	{
		$input = $request->all();
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
