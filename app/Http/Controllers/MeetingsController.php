<?php namespace App\Http\Controllers;
use App\Model\Meetings;
use App\Model\Meetingshistory;
use App\Model\Tasks;
use App\User;
use Auth;
use Request;
class MeetingsController extends Controller {
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
	public function index()
	{

		$meetings = Meetings::whereRaw('FIND_IN_SET('.Auth::id().',attendees)')->orWhereRaw('FIND_IN_SET('.Auth::id().',minuters)')->paginate(10);	
		return view('meetings.list',array('meetings'=>$meetings));
	}
	public function listAll()
	{
		$meetings = Meetings::paginate(10);
		return view('meetings.admin',array('meetings'=>$meetings));
	}
	public function getAdd()
	{
		$users = User::where('id','!=',Auth::user()->id)->lists('name','id');
		return view('meetings.add',array('users'=>$users));
	}
	public function postAdd()
	{
		$input = Request::only('title','venue','attendees','minuters');
		$validation = Meetings::validation($input);

		if ($validation->fails())
		{
			return redirect('admin/meeting/add/')->withInput($input)->with('minuters',$input['minuters'])
				->with('attendees',$input['attendees'])->withErrors($validation);
		}
		$input['updated_by'] = Auth::user()->id;
		$input['attendees'] = implode(',', $input['attendees']);
		$input['minuters'] = implode(',', $input['minuters']);
		$input['created_by'] = Auth::user()->id;
		if(Meetings::create($input))
		{
			return redirect('admin/meetings')->with('message', 'Meeting added successfully');
		}
		else
		{
			return redirect('admin/meeting/add/')->with('error', 'Oops something went wrong!')
			->withInput($input);
		}			
	}
	public function getEdit($meeting)
	{
		if($meeting)
		{
			return view('meetings.edit',['meeting'=>$meeting]);
		}
		else
		{
			abort('404','Invalid meeting');
		}
	}
	public function postEdit($meeting)
	{
		$input = Request::only('title','venue','attendees','minuters');
		$validation = Meetings::validation($input);
		if($meeting)
		{
			if($validation->fails())
			{
				return redirect('admin/meeting/'.$meeting->id.'/edit')->withErrors($validation);
			}
			else
			{
				$input['updated_by'] = Auth::user()->id;
				$input['attendees'] = implode(',', $input['attendees']);
				$input['minuters'] = implode(',', $input['minuters']);
				$meeting->update($input);
				return redirect('admin/meetings')->with('message', 'Meeting updated successfully');
			}
		}
		else
		{
			abort('404','Invalid meeting');
		}
	}
}
