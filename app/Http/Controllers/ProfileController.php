<?php namespace App\Http\Controllers;
use Auth;
use App\User;
use Request;
class ProfileController extends Controller {

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
		if($uid)
		{
			$user = User::find($uid);
		}
		else
		{
			$user = Auth::user();
		}
		return view('auth.profile',array('user'=>$user));
	}
	public function findUser()
	{	 
		$input = Request::only('term');
		$list = User::select('id','name as value')->where('name','LIKE','%'.$input['term'].'%')->get();
		return response()->json($list);
	}
	public function userlist()
	{	 
		$users = User::where('id','!=',Auth::id())->paginate(10);;
		return view('user.list',['users'=>$users]);
	}
	public function getuser(User $user)
	{
		return view('user.edit',['user'=>$user]);	
	}
	public function postuser(User $user)
	{
		//return view('user.edit',['user'=>$user]);	
	}

}
