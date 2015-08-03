<?php namespace App\Http\Controllers\Followups;
use App\Http\Controllers\Controller;
use Request;
use App\Model\JobTasks;
use App\Model\JobTaskComments;
use App\Model\Tasks;
use App\Model\MinuteTasks;
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
		$tasks = Tasks::whereAssigner(Auth::id())
					->where('status','!=','Closed')->orderBy('dueDate')->get();
		$drafts = JobDraft::where('assigner','=',Auth::id())->orderBy('updated_at','desc')->get();
		return view('followups.index',['tasks'=>$tasks,'drafts'=>$drafts]);
	}
	public function viewTask($id)
	{
		$task = JobTasks::whereId($id)->whereAssigner(Auth::id())->first();
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
				return view('followups.task',['task'=>$task]);
			}
		}
		else
		{
			abort('403');
		}
	}
}
