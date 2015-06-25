<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\JobTasks;
use App\Model\TempMeetings;
use App\Model\Meetings;
use Auth;
class MeetingsController extends Controller {

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
		$meetings = TempMeetings::all();
		return view('admin.meetings',['meetings'=>$meetings]);
	}
	public function approve(Request $request)
	{
		$input = $request->all();
		if($tempMeetings = TempMeetings::find($input['mid']))
		{
			$output['success'] = 'yes';
			$validator = TempMeetings::validation($input);
			if ($validator->fails())
			{
				$output['success'] = 'no';
				$output['validator'] = $validator->messages()->toArray();
				return json_encode($output);
			}
			else
			{
				$input['created_by'] = $tempMeetings->created_by;
				$input['updated_by'] = Auth::id();
				$input['minuters'] = implode(',',$input['minuters']);
				$input['attendees'] = implode(',',$request->input('attendees',[]));
				if(Meetings::create($input))
				{
					$tempMeetings->delete();
					return json_encode($output);
				}
			}
		}
		else
		{
			return abort('403');
		}
	}
	public function disapprove(Request $request)
	{
		$input = $request->all();
		$tempMeetings = TempMeetings::find($input['mid'])->first();
		if($tempMeetings)
		{
			if(!$input['reason'])
			{
				$output['success'] = 'no';
				$output['reason'] = 'Reason for reject is require.';
				return json_encode($output);
			}
			$output['success'] = 'yes';
			$tempMeetings->status = 'rejected';
			$tempMeetings->reason = $input['reason'];
			$tempMeetings->updated_by = Auth::id();
			$tempMeetings->save();
			return json_encode($output);
		}
		else
		{
			return abort('403');
		}
	}
}
