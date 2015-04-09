<?php namespace App\Http\Controllers;
use App\Model\Meetings;
use App\Model\Minutes;
use App\User;
use Auth;
use Request;
class MinutesController extends Controller {
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
	public function index($nid=NULL)
	{
		if($nid)
		{
			//get notes histroy for note id
			$noteshistory = Noteshistory::where('nid','=',$nid)->orderBy('updated_at','desc')->get();
			if($noteshistory->first())
			{
				$notes =NULL;
			}
			else
			{
				$notes = Notes::find($nid);
			}
			//print_r($noteshistory); die;
			return view('notes.history',array('noteshistory'=>$noteshistory,'notes'=>$notes));
		}
		else
		{
			//get all notes for current user
			$notes = Notes::where('assignee','=',Auth::user()->id)
					->where('status','!=','close')
					->orderBy('due')->get();
			return view('notes.list',array('notes'=>$notes));
		}
		
	}
	public function getAdd($meeting)
	{
		if($meeting->hasPermissoin())
		{
			if($meeting->minutes()->where('lock_flag','!=','0')->count())
			{
				return redirect('minute/'.$meeting->minutes()->where('lock_flag','!=','0')->first()->id.'/tasks/add');
			}
			return view('minutes.add',array('meeting'=>$meeting));
		}
		else
		{
			return abort(403, 'Unauthorized action.');
		}
		
		
	}
	public function postAdd($meeting)
	{
		if($meeting->hasPermissoin())
		{
			if($meeting->minutes()->where('lock_flag','!=','0')->count())
			{
				return redirect('minute/'.$meeting->minutes()->where('lock_flag','!=','0')->first()->id.'/tasks/add');
			}
			$input = Request::only('venue','attendees','dt');
			$validation = Minutes::validation($input);

			if ($validation->fails())
			{
				return redirect('nextminute/'.$meeting->id)->withInput($input)->withErrors($validation);
			}
			
			$attendeesList =  array_merge(explode(',', $meeting->attendees),explode(',', $meeting->minuters));
			$input['attendees'][] = Auth::user()->id;
			$absentees = array_diff($attendeesList, $input['attendees']);
			$input['attendees'] = implode(',', $input['attendees']);
			$input['absentees'] = implode(',', $absentees);
			$input['lock_flag']=$input['created_by']=$input['updated_by']=Auth::user()->id;
			$record = new Minutes($input);
			$result = $meeting->minutes()->save($record);
			
			return redirect('minute/'.$result->id.'/tasks/add');
		}
		else
		{
			return abort(403, 'Unauthorized action.');
		}
		
		
	}
	public function list_history($mhid)
	{
		$minute = Minutes::find($mhid);
		if($minute)
		{
			if($minute->lock_flag != 0)
			{
				if($minute->lock_flag == Auth::id() || Auth::user()->profile->role == '999')
				{
					return redirect('notes/add/'.$mhid);
				}	
			}	
			return view('meetings.history',array('minute'=>$minute));
		
		}
		else
		{
			return abort(403, 'Unauthorized action.');
		}
	}
}
