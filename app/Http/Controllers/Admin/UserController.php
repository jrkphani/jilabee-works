<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Profile;
use App\Model\Organizations;
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
	public function emailActivate($remember_token)
	{
		$email = Request::get('email',NULL);
		$user = User::whereEmail($email)->where('remember_token',$remember_token)
				->whereActive('0')->first();
		if($user)
		{
			$user->remember_token = '';
			$user->active = '1';
			$user->save();
			return view('admin.activationSuccess');
		}
		else
		{
			abort('404');
		}
	}
	public function getAdd($userId=NULL)
	{
		$meetings = Meetings::select('meetings.*')->join('organizations','meetings.oid','=','organizations.id')
					->where('organizations.customerId','=',getOrgId())->lists('title','id');
		//print_r($meetings); die;
		if($userId)
		{
			$user = Profile::join('users','profiles.userId','=','users.id')->where('users.userId','=',$userId)->first();
			$attendees = Meetings::whereRaw('FIND_IN_SET("'.$user->id.'",meetings.attendees)')
							//->join('organizations','meetings.oid','=','organizations.id')
							//->where('organizations.customerId','=',getOrgId())
							->lists('title','id');
			$minuters = Meetings::whereRaw('FIND_IN_SET("'.$user->id.'",meetings.minuters)')
							//->join('organizations','meetings.oid','=','organizations.id')
							//->where('organizations.customerId','=',getOrgId())
							->lists('title','id');
		}
		else
		{
			$user = $attendees = $minuters = NULL;
		}
		return view('admin.addUser',['meetings'=>$meetings,'user'=>$user,'attendees'=>$attendees,'minuters'=>$minuters,'roles'=>roles()]);
	}
	public function postAdd()
	{
		$input = Request::all();
		$validator = $this->registrar->validator($input);
		$output['success'] = 'yes';
		$validator->after(function($validator) use ($input)
		{
			if($domain = getDomainFromEmail($input['email']))
			{
				if(Organizations::where('domain','=',$domain)->first())
				{
					if((Organizations::where('customerId','=',getOrgId())->first()->domain) != $domain)
					{
						$validator->errors()->add('email', 'Domain registered, Please contact domain admin');
					}
				}
			}
		});
		if ($validator->fails())
		{
			if (strpos($validator->errors()->first('email'),'registered') !== false)
		 	{
			    $admin = Organizations::where('domain','=',getDomainFromEmail($input['email']))->first();
			    sendEmail($admin->email,$admin->domain,'User Registration','emails.domainUser',['email'=>$input['email']]);
			}
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
						$profile->department = $input['department'];
						$profile->gender = $input['gender'];
						$profile->phone = $input['phone'];
						$profile->role = $input['role'];
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
										if($input['roles'][$key] > $profile->role)
										{
											/*to avoid invalid role on user creation
											meeting role should be less then or equal to user role
											can not throw validation error bcz the user record are creaetd*/
											$input['roles'][$key] = $profile->role;
										}
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
							sendEmail($user->email,$profile->name,'Jotter Account','emails.addUser',['user'=>$user]);
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
	public function editUser($uid)
	{
		$user = User::where('userId','=',$uid)->first();
		if($user)
		{
			$input = Request::all();
			$input = array_filter($input);
			$validator = $this->registrar->validator($input,$user->id);
			$output['success'] = 'yes';
			if ($validator->fails())
			{
				$output['success'] = 'no';
				$output['validator'] = $validator->messages()->toArray();
				return json_encode($output);
			}
			$user->email = $input['email'];
			if(isset($input['password']) && ($input['password']))
			{
				$user->password = bcrypt($input['password']);
			}
			if($user->save())
			{
				$profile = $user->profile;
				$profile->name = ucwords(strtolower($input['name']));
				$profile->dob = $input['dob'];
				if(!isset($input['department']))
				{
					$input['department']='';
				}
				$profile->department = $input['department'];
				$profile->gender = $input['gender'];
				$profile->phone = $input['phone'];
				$profile->role = $input['role'];
				$profile->updated_by = Auth::id();
				if($profile->save())
				{
					if(isset($input['removeMeetings']))
					{
						foreach ($input['removeMeetings'] as $key=>$value)
						{
							if($meeting = Meetings::whereId($value)->first())
							{
								$minuters = explode(',',$meeting->minuters);
								$index = array_search($user->id, $minuters);
								unset($minuters[$index]);
								$attendees = explode(',',$meeting->attendees);
								$index = array_search($user->id, $attendees);
								unset($attendees[$index]);
								//print_r($minuters);
								$meeting->attendees = implode(',',$attendees);
								$meeting->minuters = implode(',',$minuters);
								$meeting->save();
								// $output['success'] = 'no';
								// echo "Dvdf"; die;
							}
						}
					}

					if(count($input['meetings']) == count($input['roles']))
					{
						foreach ($input['meetings'] as $key=>$value)
						{
							if($value)
							{
								foreach ($input['roles'] as $key=>$role)
								{
									if($role > $profile->role)
									{
										$output['success'] = 'no';
										$validator->errors()->add("roles","Inavalid role");
										$output['validator'] = $validator->messages()->toArray();
										return json_encode($output);
									}
								}
								if($meeting = Meetings::whereId($value)->first())
								{
									$minuters = explode(',',$meeting->minuters);
									$attendees = explode(',',$meeting->attendees);
									if(in_array($user->id,$minuters) || in_array($user->id,$attendees))
									{
										//already user is there 
										//skip
										continue;
									}
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
					sendEmail($user->email,$profile->name,'Jotter Account Modified','emails.editUser',['user'=>$user]);
				}
			}
			return json_encode($output);
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
			$user = Profile::join('users','profiles.userId','=','users.id')->where('users.userId','=',$userId)->first();
			if (strpos($user->userId,$orgId) !== false)
			{
				$attendees = Meetings::whereRaw('FIND_IN_SET("'.$user->id.'",meetings.attendees)')
							//->join('organizations','meetings.oid','=','organizations.id')
							//->where('organizations.customerId','=',getOrgId())
							->lists('title','id');
				$minuters = Meetings::whereRaw('FIND_IN_SET("'.$user->id.'",meetings.minuters)')
							//->join('organizations','meetings.oid','=','organizations.id')
							//->where('organizations.customerId','=',getOrgId())
							->lists('title','id');
				return view('admin.viewUser',['user'=>$user,'attendees'=>$attendees,'minuters'=>$minuters,'roles'=>roles()]);
			}
			else
			{
				abort("403");
			}
		}
	}
}