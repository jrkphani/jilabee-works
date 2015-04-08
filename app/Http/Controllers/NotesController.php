<?php namespace App\Http\Controllers;
use App\Model\Notes;
use App\Model\Minutes;
use App\Model\Minuteshistory;
use App\Model\Noteshistory;
use App\Model\Notesdraft;
use App\User;
use Auth;
use Request;
class NotesController extends Controller {
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
			$notes = Notes::find($nid);
			return view('notes.history',array('notes'=>$notes));
		}
		else
		{
			$input = Request::only('sortby');
			//get all notes for current user
			if($input['sortby'] == 'duedate' || !($input['sortby']))
			{
				$notes = Notes::where('assignee','=',Auth::user()->id)
					->where('status','!=','close')
					->orderBy('due')->paginate(15);
				return view('notes.list_duedate',array('notes'=>$notes,'input'=>$input));
			}
			else if($input['sortby'] == 'assigner')
			{
				$notes = Notes::where('assignee','=',Auth::user()->id)
					->where('status','!=','close')
					->orderBy('assigner')->paginate(15);
				return view('notes.list_assigner',array('notes'=>$notes,'input'=>$input));
			}
			else if($input['sortby'] == 'meeting')
			{
				$notes = Notes::select('notes.*')->where('notes.assignee','=',Auth::user()->id)
					->where('notes.status','!=','close')
					->join('minutes_history','notes.mhid','=','minutes_history.id')
					->join('minutes','minutes_history.mid','=','minutes.id')
					->orderBy('minutes.id')->paginate(15);
				return view('notes.list_meeting',array('notes'=>$notes,'input'=>$input));
			}
			else
			{
				$notes = Notes::where('assignee','=',Auth::user()->id)
					->where('status','!=','close')
					->orderBy('due')->paginate(15);
				return view('notes.list_duedate',array('notes'=>$notes,'input'=>$input));
			}

		}
		
	}
	public function getAdd($mhid)
	{
		if($minuteshistory = Minuteshistory::find($mhid))
		{
			if($minuteshistory->lock_flag == Auth::id() || Auth::user()->profile->role == '999')
			{
				//$users = User::lists('name','id');
				$users = User::whereIn('id',explode(',', $minuteshistory->attendees))->lists('name','id');
				return view('notes.add',['minuteshistory'=>$minuteshistory,'users'=>$users]);	
			}
			else
			{
				return redirect('minute/'.$minuteshistory->mid);
			}
			//print_r($minuteshistory->minute->title); die;
			
		}
		// else if($minute = Minutes::find($id))
		// {
		// 	$input = new Minuteshistory(array('lock'=>Auth::user()->id,'created_by'=>Auth::user()->id,'updated_by'=>Auth::user()->id));
		// 	$minuteshistory = $minute->minute_history()->save($input);
		// 	$users = User::lists('name','id');
		// 	$notesdraft = NULL;
		// 	return view('notes.add',['minuteshistory'=>$minuteshistory,'users'=>$users,'notesdraft'=>$notesdraft]);
			
		// }
		else
		{
			return Response::make('Not Found', 404);
		}
		
	}
	public function postDraft($id)
	{

		$message = $error = '';
		$input = Request::only('title', 'description','assignee','assigner','due');
		$records=array();
		for ($i=0; $i < count($input['title']); $i++)
		{ 
			$tempArr['title'] = trim($input['title'][$i]);
			$tempArr['description'] = trim($input['description'][$i]);
			$tempArr['assignee'] = $input['assignee'][$i];
			$tempArr['assigner'] = $input['assigner'][$i];
			$tempArr['due'] = $input['due'][$i];
			$tempArr['created_by'] = Auth::user()->id;
			if(($tempArr['title']) || ($tempArr['description']))
			$records[] = new Notesdraft(array_filter($tempArr));
		}
		//print_r($records); die;
		if($records)
		{
			$minuteshistory = Minuteshistory::find($id);
			$minuteshistory->notes_draft()->delete();
			$minuteshistory->notes_draft()->saveMany($records);
		}
		else
		{
			$error = "Error DB500";
		}
		//return redirect('user/login')->with('message', $message)->with('error', $error);
		
	}
	public function postAdd($id)
	{
		//validation has to be done
		$this->postDraft($id);
		$input = Request::only('title', 'description','assignee','assigner','due');
		$records=array();
		for ($i=0; $i < count($input['title']); $i++)
		{ 
			$tempArr= array();
			$tempArr['title'] = trim($input['title'][$i]);
			$tempArr['description'] = trim($input['description'][$i]);
			$tempArr['assignee'] = $input['assignee'][$i];
			$tempArr['assigner'] = $input['assigner'][$i];
			$tempArr['due'] = $input['due'][$i];
			$tempArr['created_by'] = $tempArr['updated_by'] = Auth::user()->id;
			$validation = Notes::validation($tempArr);
			if ($validation->fails())
			{
				return redirect('notes/add/'.$id)->withErrors($validation);
			}

			if(($tempArr['title']) && ($tempArr['description']))
			$records[] = new Notes(array_filter($tempArr));
		}
		if($records)
		{
			$minuteshistory = Minuteshistory::find($id);		
			if($minuteshistory->notes()->saveMany($records))
			{
				$minuteshistory->update(array('lock_flag'=>'0'));
				$minuteshistory->notes_draft()->delete();
				return redirect('minutehistory/'.$id);
			}
		}
		else
		{
			$error = "Error DB500";
		}
		//return redirect('user/login')->with('message', $message)->with('error', $error);
	}
	public function postComment($nid)
	{
		$input = Request::only('description');
		$input['created_by'] = $input['updated_by'] = Auth::user()->id;
		$notes = Notes::find($nid);
		$record = new Noteshistory($input);
		$notes->notes_history()->save($record);
		return view('notes.history',array('notes'=>$notes));
	}
	public function followup()
	{

			$input = Request::only('sortby');
			//get all notes for current user
			if(Auth::user()->profile->role == '999')
			{
				$notes = Notes::select('notes.*')->where('notes.status','!=','close')->join('minutes_history','notes.mhid','=','minutes_history.id')
							->join('minutes','minutes_history.mid','=','minutes.id');
			}
			else
			{
				$notes = Notes::select('notes.*')->join('minutes_history','notes.mhid','=','minutes_history.id')
							->join('minutes','minutes_history.mid','=','minutes.id')
							->where('notes.assigner','=',Auth::user()->id)
							->orWhere(function($query){
								$query->whereRaw('FIND_IN_SET('.Auth::id().',minutes.minuters)');
							})
							->where('notes.status','!=','close');
			}
			if($input['sortby'] == 'duedate' || !($input['sortby']))
			{
				$notes = $notes->orderBy('notes.due')->paginate(15);
				return view('notes.list_duedate',array('notes'=>$notes,'input'=>$input));
			}
			else if($input['sortby'] == 'assignee')
			{
				$notes = $notes->orderBy('notes.assignee')->paginate(15);
				return view('notes.list_assignee',array('notes'=>$notes,'input'=>$input));
			}
			else if($input['sortby'] == 'meeting')
			{
				$notes = $notes->orderBy('minutes.id')->paginate(15);
				return view('notes.list_meeting',array('notes'=>$notes,'input'=>$input));
			}
			else
			{
				$notes = $notes->orderBy('notes.due')->paginate(15);
				return view('notes.list_duedate',array('notes'=>$notes,'input'=>$input));
			}
	}

	public function accept($id)
	{
		$input = Request::only('description');
		$notes = Notes::where('id','=',$id)->whereRaw('FIND_IN_SET('.Auth::id().',assignee)')->first();
		if($notes)
		{
			$notes->update(['status'=>'open','updated_by'=>Auth::id()]);
			if($input['description'])
			{
				$input['created_by'] = $input['updated_by'] = Auth::user()->id;
				$record = new Noteshistory($input);
				$notes->notes_history()->save($record);
			}
			return redirect('notes/'.$id);		
		}
		else
		{
			abort('403','Unauthorized access');
		}
	}
	public function reject($id)
	{
		$input = Request::only('description');
		$notes = Notes::where('id','=',$id)->whereRaw('FIND_IN_SET('.Auth::id().',assignee)')->first();
		if($notes)
		{
			$validation = Noteshistory::validation($input);
			if ($validation->fails())
			{
				return redirect('notes/'.$id)->withErrors($validation);
			}
			else
			{
				$notes->update(['status'=>'rejected','updated_by'=>Auth::id()]);
				$input['created_by'] = $input['updated_by'] = Auth::user()->id;
				$record = new Noteshistory($input);
				$notes->notes_history()->save($record);
				return redirect('notes/'.$id);
			}
		}
		else
		{
			abort('403','Unauthorized access');
		}
	}
	public function edit($nid)
	{
		$notes = Notes::find($nid);
		$users = User::whereIn('id',explode(',', $notes->minute_history->attendees))->lists('name','id');
		return view('notes.edit',['notes'=>$notes,'users'=>$users]);
	}
	public function update($nid)
	{
		$input = Request::only('title', 'description','assignee','assigner','due');
		$validation = Notes::validation($input);
		if ($validation->fails())
		{
			return redirect('notes/edit/'.$nid)->withErrors($validation);
		}
		else
		{
			$input['updated_by'] = Auth::user()->id;
			$input['status'] = 'waiting';
			$notes = Notes::find($nid);
			$notes->update($input);
			return "updated";
		}
		
	}

}
