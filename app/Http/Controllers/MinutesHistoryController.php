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
		$users = User::where('id','!=',Auth::user()->id)->lists('name','id');
		$minutes = Minutes::find($id);
		return view('minutes.add_history',array('minutes'=>$minutes,'users'=>$users));
		
	}
	public function postAdd($id)
	{
		$input = Request::only('venue','attendees');
		$input['attendees'] = implode(',', $input['attendees']);
		$input['lock_flag']=$input['created_by']=$input['updated_by']=Auth::user()->id;
		if(Minuteshistory::where('mid','=',$id)->where('lock_flag','=',Auth::user()->id)->count())
		{
			Minuteshistory::where('mid','=',$id)->update($input);
		}
		else
		{
			$record = new Minuteshistory($input);
			$minutes = Minutes::find($id);
			$minutes->minute_history()->save($record);	
		}
		
	}
}
