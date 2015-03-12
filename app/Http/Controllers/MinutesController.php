<?php namespace App\Http\Controllers;
use App\Model\Minutes;
use App\Model\Notes;
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
	public function index()
	{
		return view('minutes.home');
	}
	
	public function showAdd()
	{
		//print_r(Minutes::all());
		return view('minutes.add');
	}
	public function postAdd()
	{
		$message = $error = '';
		$input = Request::only('title', 'label','venue');
		$input['created_by'] = $input['updated_by'] = Auth::user()->id;
		if(Minutes::create($input))
		{
			$message = "Minute added successfully";
		}
		else
		{
			$error = "Error DB500";
		}
		return redirect('/home')->with('message', $message)->with('error', $error);
	}
	public function list_minutes()
	{
		$minutes = Minutes::orderBy('updated_at', 'desc')->get();
		//print_r($minutes); die;
		return view('minutes.list',array('minutes'=>$minutes));
	}
	public function list_history($id)
	{
		$notes = Notes::select('notes.*')->join('minutes_history','notes.mhid','=','minutes_history.id')
				->where('minutes_history.mid','=',$id)->orderBy('notes.mhid')
				->get();
		return view('minutes.history',array('notes'=>$notes));
	}
}
