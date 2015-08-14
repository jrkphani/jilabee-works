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
use App\Model\Meetings;
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
		$meetings = Meetings::select('meetings.*')->join('organizations','meetings.oid','=','organizations.id')
					->where('organizations.customerId','=',getOrgId())->get();
		return view('admin.addUser',['meetings'=>$meetings]);
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
							if(count($input['meetings']) == count($input['roles']))
							{
								foreach ($input['meetings'] as $key=>$value)
								{
									if($meeting = Meetings::find($value))
									{
										if($input['roles'][$key] == 1)
										{
											$meeting->attendees = $meeting->attendees.','.$user->id;
										}
										else if($input['roles'][$key] == 2)
										{
											$meeting->minuters = $meeting->minuters.','.$user->id;
										}
										$meeting->save();
									}
								}
							}

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
		//only view user inside same org
		if($orgId = getOrgId())
		{
			$users = Profile::select('profiles.*','users.userId')->join('users','profiles.userId','=','users.id')->where('users.userId','LIKE',$orgId.'%')->get();
			return view('admin.userList',['users'=>$users]);
		}
	}
	public function getUser($userId)
	{
		//only view user inside same org
		if($orgId = getOrgId())
		{
			if (strpos(Auth::user()->userId,$orgId) !== false)
			{
	    		$user = Profile::join('users','profiles.userId','=','users.id')->where('users.userId','=',$userId)->first();
				return view('admin.viewUser',['user'=>$user]);
			}
		}
	}
}