<?php namespace App\Http\Controllers\Meetings;
use App\Http\Controllers\Controller;
use Request;
use App\Model\MinuteTasks;
use App\Model\Minutes;
use Auth;
use DB;
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
		//return view('jobs.index');
	}

	public function createTask($mid)
	{
		$input = Request::only('title','description','assignee','assigner','dueDate','type');
		$output['success'] = 'yes';
		$records= $ideasArr = array();
		for ($i=0; $i < count($input['title']); $i++)
		{ 
			$tempArr= array();
			$tempArr['title'] = trim($input['title'][$i]);
			$tempArr['description'] = trim($input['description'][$i]);
			if(($tempArr['title']) && ($tempArr['description']))
			{
				if($input['type'][$i] == 'task')
				{
					$tempArr['assignee'] = $input['assignee'][$i];
					$tempArr['assigner'] = $input['assigner'][$i];
					$tempArr['dueDate'] = $input['dueDate'][$i];
					$tempArr['created_by'] = $tempArr['updated_by'] = Auth::id();
					$validator = MinuteTasks::validation($tempArr);
					if ($validator->fails())
					{
						$output['success'] = 'no';
						$output['validator'] = $validator->messages()->toArray();
						return json_encode($output);
					}
					//if(($tempArr['title']) && ($tempArr['description']))
					$records[] = new MinuteTasks(array_filter($tempArr));	
				}
				else
				{
					// $tempArr['orginators'] = $input['orginators'][$i];
					// $tempArr['created_by'] = $tempArr['updated_by'] = Auth::user()->id;
					// $ideasArr[] = new Ideas(array_filter($tempArr));
				}
			}
			
		}
		if($records )
		{	
			//$minute->ideas()->saveMany($ideasArr);
			// if($minute->tasks()->saveMany($records))
			// {
			// 	$minute->update(array('lock_flag'=>'0'));
			// 	$minute->tasks_draft()->delete();
			// 	return redirect('/#meetings#minute'.$minute->id);
			// }
			DB::transaction(function() use ($mid,$records)
			{
				$minute = Minutes::where('id','=',$mid)->where('lock_flag','=',Auth::id())->first();
				$minute->tasks()->saveMany($records);
				$minute->update(array('lock_flag'=>null));
				$minute->draft()->delete();
				$output['meetingId'] = $minute->meetingId;
			});
			return json_encode($output);

		}
		else
		{
			$output['success'] = 'no';
			$output['success'] = 'Empty fields';
			return json_encode($output);
		}

		$validator = MinuteTasks::validation($input);
		// if ($validator->fails())
		// {
		// 	$output['success'] = 'no';
		// 	$output['validator'] = $validator->messages()->toArray();
		// 	return json_encode($output);
		// }
		// else
		// {
		// 	$input['created_by'] = $input['updated_by'] = $input['assigner'] = Auth::id();
		// 	MinuteTasks::create($input);
		// 	return json_encode($output);
		// }
	}
}
