<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use App\User;
use Request;
use Validator;
use App\Model\Profile;
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
		$list = Profile::select('users.userId','name as value')->join('users','profiles.userId','=','users.id')
				->where('profiles.name','LIKE','%'.trim($input['term']).'%')
				->orWhere('users.email','LIKE','%'.trim($input['term']).'%')
				->get();
		return response()->json($list);
	}
	// public function userlist()
	// {	 
	// 	echo Session::get('database'); die;
	// 	if(Auth::user()->isAdmin)
	// 	{
	// 		//$userId
	// 		$users = User::where('userId')->where('id','!=',Auth::id())->paginate(10);;
	// 		return view('user.list',['users'=>$users]);
	// 	}
	// 	return abort('403');
	// }
	public function getedit()
	{
		return view('user.selfedit',['user'=>Auth::user()]);	
	}
	public function postedit()
	{
		$user= Auth::user();
		$input = Request::only('name','dob','phone','password','password_confirmation','gender');
		$input = array_filter($input);
		$validator = Validator::make($input, [
			'name' => 'required|max:255',
			'password' => 'confirmed|min:6',
			'phone'	=>'required|Regex:/^([0-9\s\-\+\(\)]*)$/',
			'dob' =>'required|date|date_format:Y-m-d',
			'gender' =>'required|in:M,F,O',
		]);

		if ($validator->fails())
		{
			return redirect('profile/edit')->withErrors($validator);
		}
		$userinput = ['name'=>$input['name'],'update_by' => Auth::id()];
		if(isset($input['password']))
		{
			$userinput['password']=bcrypt($input['password']);
		}
		if($user->update(array_filter($userinput)))
		{
			$profileinput = ['dob'=>$input['dob'],'phone'=>$input['phone'],
							'gender'=>$input['gender'],'update_by' => Auth::id()];
			if($user->profile->update($profileinput))
			{
				return redirect('profile/edit')->with('message', 'Updated successfully!');
			}
		}
	}
	public function getuser(User $user)
	{
		return view('user.edit',['user'=>$user]);	
	}
	public function postuser(User $user)
	{
		$input = Request::only('name','email','dob','phone','password','password_confirmation','gender','role');
		$input = array_filter($input);
		$validator = Validator::make($input, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users,email,' . $user->id,
			'password' => 'confirmed|min:6',
			'role'	=>'required|integer',
			'phone'	=>'required|Regex:/^([0-9\s\-\+\(\)]*)$/',
			'dob' =>'required|date|date_format:Y-m-d',
			'gender' =>'required|in:M,F,O',
		]);

		if ($validator->fails())
		{
			return redirect('user/'.$user->id.'/edit')->withErrors($validator);
		}
		$userinput = ['name'=>$input['name'],'email'=>$input['email'],'update_by' => Auth::id()];
		if(isset($input['password']))
		{
			$userinput['password']=bcrypt($input['password']);
		}
		if($user->update(array_filter($userinput)))
		{
			$profileinput = ['dob'=>$input['dob'],'phone'=>$input['phone'],
							'gender'=>$input['gender'],'role'=>$input['role']
							,'update_by' => Auth::id()];
			if($user->profile->update($profileinput))
			{
				return redirect('user/'.$user->id.'/edit')->with('message', 'Updated successfully!');
			}
		}
	}

}
