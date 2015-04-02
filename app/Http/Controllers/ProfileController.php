<?php namespace App\Http\Controllers;
use Auth;
use App\User;
use Request;
use Validator;
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
		$input = Request::only('name','email','dob','phone','password','password_confirmation','gender','role');
		$validator = Validator::make($input, [
			'name' => 'required|max:255',
			'email' => 'email|max:255|unique:users,email,' . $user->id,
			'password' => 'confirmed|min:6',
			'role'	=>'required|integer',
			'phone'	=>'Regex:/^([0-9\s\-\+\(\)]*)$/',
			'dob' =>'required|date|date_format:Y-m-d',
			'gender' =>'required|in:M,F,O',
		]);

		if ($validator->fails())
		{
			return redirect('edit/user/'.$user->id)->withErrors($validator);
		}
		$userinput = ['name'=>$input['name'],'email'=>$input['email'],
					'password'=>bcrypt($input['password']),'update_by' => Auth::id()];
		if($user->update(array_filter($userinput)))
		{
			$profileinput = ['dob'=>$input['dob'],'phone'=>$input['phone'],
							'gender'=>$input['gender'],'role'=>$input['role']
							,'update_by' => Auth::id()];
			if($user->profile->update($profileinput))
			{
				return redirect('edit/user/'.$user->id)->with('message', 'Updated successfully!');
			}
		}
	}

}
