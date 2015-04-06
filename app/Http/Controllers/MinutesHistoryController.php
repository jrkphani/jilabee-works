<?php namespace App\Http\Controllers;
use App\Model\Minutes;
use App\Model\Minuteshistory;
use App\User;
use Auth;
use Request;
class MinutesHistoryController extends Controller {
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
	public function getAdd($id)
	{
		//$users = User::where('id','!=',Auth::user()->id)->lists('name','id');
		$minutes = Minutes::find($id);
		if($minutes->hasPermissoin())
		{
			if($minute_history = $minutes->minute_history()->where('lock_flag','!=','0')->count())
			{
				return redirect('notes/add/'.$minutes->minute_history()->where('lock_flag','!=','0')->first()->id);
			}
			return view('minutes.add_history',array('minutes'=>$minutes));
		}
		else
		{
			return abort(403, 'Unauthorized action.');
		}
		
		
	}
	public function postAdd($id)
	{
		$minutes = Minutes::find($id);
		if($minutes->hasPermissoin())
		{
			$input = Request::only('venue','attendees');
			$validation = Minuteshistory::validation($input);

			if ($validation->fails())
			{
				return redirect('minutehistory/add/'.$id)->withInput($input)->withErrors($validation);
			}
			$attendeesList =  array_merge(explode(',', $minutes->attendees),explode(',', $minutes->minuters));
			$input['attendees'][] = Auth::user()->id;
			$absentees = array_diff($attendeesList, $input['attendees']);
			$input['attendees'] = implode(',', $input['attendees']);
			$input['absentees'] = implode(',', $absentees);
			$input['lock_flag']=$input['created_by']=$input['updated_by']=Auth::user()->id;
			if(Minuteshistory::where('mid','=',$id)->where('lock_flag','=',Auth::user()->id)->count())
			{
				$result = Minuteshistory::where('mid','=',$id)->where('lock_flag','=',Auth::user()->id)->update($input);
			}
			else
			{
				$record = new Minuteshistory($input);
				$result = $minutes->minute_history()->save($record);
			}
			return redirect('notes/add/'.$result->id);
		}
		else
		{
			return abort(403, 'Unauthorized action.');
		}
		
		
	}
	public function list_history($mhid)
	{
		$minute_history = Minuteshistory::find($mhid);
		if($minute_history)
		{
			if($minute_history->lock_flag != 0)
			{
				if($minute_history->lock_flag == Auth::id() || Auth::user()->profile->role == '999')
				{
					return redirect('notes/add/'.$mhid);
				}	
			}	
			return view('minutes.history',array('minuteshistory'=>$minute_history));
		
		}
		else
		{
			return abort(403, 'Unauthorized action.');
		}
	}
}
