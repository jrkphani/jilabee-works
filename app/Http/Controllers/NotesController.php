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
	public function showAdd($id)
	{
		//if($minuteshistory = Minuteshistory::where('mid','=',$id)->first())
		if(0)
		{
			//print_r($minuteshistory->minute->title); die;
			$users = User::lists('name','id');
			$notesdraft = Notesdraft::where('mhid','=',$minuteshistory->id)->get();
			//print_r($notesdraft);
			return view('notes.add',['minuteshistory'=>$minuteshistory,'users'=>$users,'notesdraft'=>$notesdraft]);
		}
		else if($minute = Minutes::find($id))
		{
			$input = array('mid'=>$id,'lock'=>Auth::user()->id,'created_by'=>Auth::user()->id,'updated_by'=>Auth::user()->id);
			$minuteshistory = Minuteshistory::create($input);
			$users = User::lists('name','id');
			$notesdraft = NULL;
			return view('notes.add',['minuteshistory'=>$minuteshistory,'users'=>$users,'notesdraft'=>$notesdraft]);
			
		}
		else
		{
			return Response::make('Not Found', 404);
		}
		
	}
	public function postDraft($id)
	{

		$message = $error = '';
		$input = Request::only('title', 'description','assignee','priority');
		$inserArr=array();
		for ($i=0; $i < count($input['title']); $i++)
		{ 
			$tempArr['title'] = trim($input['title'][$i]);
			$tempArr['description'] = trim($input['description'][$i]);
			$tempArr['assignee'] = $input['assignee'][$i];
			$tempArr['created_by'] = Auth::user()->id;
			if(($tempArr['title']) && ($tempArr['description']))
			$records[] = new Notesdraft(array_filter($tempArr));
		}
		//print_r($records); die;
		if($records)
		{
			$minuteshistory = Minuteshistory::find($id);
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
		$message = $error = '';
		$input = Request::only('title', 'description','assignee','priority');
		$inserArr=array();
		for ($i=0; $i < count($input['title']); $i++)
		{ 
			$tempArr['title'] = trim($input['title'][$i]);
			$tempArr['description'] = trim($input['description'][$i]);
			$tempArr['assignee'] = $input['assignee'][$i];
			$tempArr['created_by'] = $tempArr['updated_by'] = Auth::user()->id;
			if(($tempArr['title']) && ($tempArr['description']))
			$records[] = new Notes(array_filter($tempArr));
		}
		//print_r($records); die;
		if($records)
		{
			$minuteshistory = Minuteshistory::find($id);
			$minuteshistory->notes()->saveMany($records);
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
		$noteshistory = Noteshistory::where('nid','=',$nid)->orderBy('updated_at','desc')->get();
		return view('notes.history',array('noteshistory'=>$noteshistory,'notes'=>NULL));
	}

}
