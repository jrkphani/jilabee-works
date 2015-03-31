<?php namespace App\Http\Controllers;
use Auth;
use Request;
use App\Model\Stickynotes;
class SticknotesController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index($uid=NULL)
	{
		$result = Stickynotes::where('created_by',Auth::id())->get();
		return view('stickynotes',['stickynotes'=>$result]);
	}
	public function postData()
	{	 
		$input = Request::only('stick_text');
		
	}

}
