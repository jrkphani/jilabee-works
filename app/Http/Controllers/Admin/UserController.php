<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Profile;
use Illuminate\Contracts\Auth\Guard;
use DB;
use Auth;
use Validator;
use Activity;
use Request;
use Session;
use Illuminate\Contracts\Auth\Registrar;
class UserController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->registrar = $registrar;
	}
	public function getAdd()
	{
		return view('admin.addUser');
	}
	public function postAdd()
	{
		$input = Request::all();
		$validator = $this->registrar->validator($input);
		$output['success'] = 'yes';
		if ($validator->fails())
		{
			$output['success'] = 'no';
			$output['validator'] = $validator->messages()->toArray();
			return json_encode($output);
		}
		try
		 {
		 	DB::transaction(function() use ($input)
		 	{
		 		$user = new User();
				$user->email = $input['email'];
				$user->active ='1';
				$user->userId = "dumy".date('His');
				$user->password = bcrypt($input['password']);
				if($user->save())
				{
					if($orgId = getOrgId())
					{
						$userId = generateUserId($orgId,$user->id);	
						$user->update(['userId'=>$userId]);
						$profile = new Profile();
						$profile->userId = $user->id;
						$profile->name = ucwords(strtolower($input['name']));
						$profile->dob = $input['dob'];
						$profile->gender = $input['gender'];
						$profile->phone = $input['phone'];
						$profile->created_by = Auth::id();
						$profile->updated_by = Auth::id();
						if($user->profile()->save($profile))
						{
							Activity::log([
							'userId'	=> Auth::id(),
							'contentId'   => $user->id,
						    'contentType' => 'Add Organizations User',
						    'action'      => 'Create',
						    //'description' => 'Add Organizations User',
						    'details'     => 'Name:'.$input['name'].'Email:'.$input['email']
						]);
						}
					}
				}
		 	});
			return json_encode($output);
		 }
		 catch (Exception $e)
		 {
	        //error
	        //DB::connection('jotterBase')->rollback();
    	}	
	}
	public function userList()
	{
		$users = Profile::select('profiles.*','users.userId')->join('users','profiles.userId','=','users.id')->where('users.userId','LIKE',Session::get('database').'%')->get();
		return view('admin.userList',['users'=>$users]);
	}
	public function getUser($userId)
	{
		//only view user inside same org
		if (strpos(Auth::user()->userId,Session::get('database')) !== false)
		{
    		$user = Profile::join('users','profiles.userId','=','users.id')->where('users.userId','=',$userId)->first();
			return view('admin.viewUser',['user'=>$user]);
		}
	}
}