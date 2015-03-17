<?php namespace App\Http\Controllers;
use App\Model\Minutes;
use App\Model\Minuteshistory;
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
	
	public function getAdd()
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
		$minutes = Minutes::find($id);
		// echo "<pre>";
		//echo "i am here";
		// print_r($notes);
		//die;
		if($minutes->minute_history()->where('lock_flag','=',Auth::user()->id)->count())
		{
			//redirect to add notes page if same user
			$mhid = $minutes->minute_history()->where('lock_flag','=',Auth::user()->id)->first()->id;
			return redirect('notes/add/'.$mhid);
		}
		else
		{
			return view('minutes.history',array('minutes'=>$minutes));
		}
	}
}
