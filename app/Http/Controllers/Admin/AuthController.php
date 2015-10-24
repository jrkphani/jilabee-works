<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Organizations;
use App\Model\OrganizationInfo;
use App\User;
use App\Model\Clients;
use App\Model\Profile;
use Activity;
use Artisan;
use Auth;
use Session;
use DB;
use App;
use Validator;
class AuthController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('admin',['except'=>['logout']]);
	}
	public function signupGet()
	{
		return view('admin.register');
	}
	public function signupPost(Request $request)
	{
		$validator = Organizations::validation($request->all());
		$input = $request->all();
		$validator->after(function($validator) use ($input)
		{
			if($domain = getDomainFromEmail($input['email']))
			{
				if(Organizations::where('domain','=',$domain)->first())
				{
					$validator->errors()->add('email', 'Domain registered, Please contact domain admin');
				}
			}
		});
		 if ($validator->fails())
		 {
		 	if (strpos($validator->errors()->first('domain'),'taken') !== false)
		 	{
			    $admin = Organizations::where('domain','=',$input['domain'])->first();
			    sendEmail($admin->email,$admin->domain,'Duplicate Domain Registration','emails.duplicateDomain',['admin'=>$admin]);
			}
			if (strpos($validator->errors()->first('email'),'registered') !== false)
		 	{
			    $admin = Organizations::where('domain','=',getDomainFromEmail($input['email']))->first();
			    sendEmail($admin->email,$admin->domain,'User Registration','emails.domainUser',['email'=>$input['email']]);
			}
		 	return redirect('admin/auth/register')->withInput($input)->with('isOrg','yes')->withErrors($validator);
		 }
		DB::transaction(function() use ($input)
		 {
		      $organizations = Organizations::create([
					'email' => $input['email'],
					'secondEmail' => $input['secondEmail'],
					'customerId' => "dumy".date('His'),
					'domain' => $input['domain']
				]);
				if($organizations)
				{
					$customerId = generateCustomerId($organizations->id);
					$organizations->update(['customerId'=>$customerId]);
					$orgInput  = array('customerId'=>$customerId,
									'orgName'=>$input['name'],
									'phone'=>$input['phone'],
									'phone1'=>$input['phone1']);

					$organizationInfo = new OrganizationInfo($orgInput);
					if($organizations->organizationInfo()->save($organizationInfo))
					{
						$user = User::create([
							'email' => $input['email'],
							'userId' => "dumy".date('His'),
							'isAdmin' => '1',
							'password' => bcrypt($input['password'])
							]);
						if($user)
						{
							$userId = generateUserId($customerId,$user->id);
							$remember_token= str_random('60');
							$user->remember_token=$remember_token;
							$user->update(['userId'=>$userId]);
							$profile = new Profile(['phone'=>$input['phone'],'dob'=>$input['dob'],'gender'=>$input['gender'],'name'=>ucwords(strtolower($input['adminname']))
								,'created_by'=>$user->id,'updated_by'=>$user->id]);
						    $user->profile()->save($profile);
						    sendEmail($user->email,$profile->name,'Activation Mail','emails.orgSignup',['user'=>$user,'remember_token'=>$remember_token]);
						}
					}					
				}
		 });
		return redirect('admin/auth/register')->with('message', 'Registration success, please check your email for activation.');
	}
	
	public function loginGet()
	{
		return view('admin.login');
	}
	public function loginPost(Request $request)
	{
		$input = $request->all();
        $validatorRule =['email'=>'required|email',
            'password'=>'required'];
        $validator = Validator::make($input,$validatorRule);
		if ($validator->fails())
		{
			return redirect('admin/auth/login')->withInput($input)->withErrors($validator);
		}
		// $domain = Clients::where('domain','=',$this->getDomainFromEmail($input['email']))->first();
		// if(!$domain)
		// {
		// 	$errors = $validator->messages();
		//     $errors->add('email', 'Invalid credentials');
		// 	return redirect('admin/auth/login')->withInput($input)->withErrors($errors);
		// }
		if (Auth::attempt(['email' => $input['email'], 'password' => $input['password'],'active'=>1,'isAdmin'=>1]))
        {
        	/*if(starts_with(Auth::user()->userId, 'GEN'))
            {
            	Session::put('database', 'jotterGeneral');
            	//do nothing 
            	//changedatabase middleware will connect to general database connection "client"
            }
            else
            {
            	//get the client database to session
            	//echo substr(Auth::user()->userId, 0, strrpos( Auth::user()->userId, 'u')); die;
            	Session::put('database', substr(Auth::user()->userId, 0, strrpos(Auth::user()->userId, 'u')));
            }*/
        	return redirect('/admin');
        }
        else
        {
        	$errors = $validator->messages();
		    $errors->add('email', 'Invalid credentials');
        	return redirect('admin/auth/login')->withInput($input)->withErrors($errors);
        }
	}
	public function logout()
	{
		Auth::logout();
		return redirect('/admin/auth/login');
	}
}
