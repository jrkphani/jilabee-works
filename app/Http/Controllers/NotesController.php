<?php namespace App\Http\Controllers;
use App\Model\Notes;
use App\Model\Minutes;
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
	public function index()
	{
		return view('minutes.home');
	}
	public function mytask()
	{
		//sleep(5);
		//echo $dgdf;
		return view('minutes.mytask');
	}
	public function showAdd($id)
	{
		if($minute = Minutes::find($id))
		{
			$users = User::all();
			return view('notes.add',['minute'=>$minute,'users'=>$users]);
		}
		else
		{
			return Response::make('Not Found', 404);
		}
		
	}
	public function postAdd()
	{
		$message = $error = '';
		$input = Request::only('title', 'label');
		$input['created_by'] = $input['updated_by'] = Auth::user()->id;
		if(Minutes::create($input))
		{
			$message = "Minute added successfully";
		}
		else
		{
			$error = "Error DB500";
		}
		return redirect('user/login')->with('message', $message)->with('error', $error);
	}
}
