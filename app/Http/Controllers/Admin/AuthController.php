<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Organizations;
use App\Model\OrganizationInfo;
use App\User;
use App\Model\Clients;
use App\Model\Profile;
use Artisan;
use Auth;
use Session;
use DB;
use App;
use Validator;
class AuthController extends Controller {
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
		$this->middleware('guest', ['except' => ['getRegister','postRegister','getLogout']]);
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function signupGet()
	{
		return view('admin.register');
	}
	public function signupPost(Request $request)
	{
		$validator = Organizations::validation($request->all());
		$input = $request->all();
		 if ($validator->fails())
		 {
		 	return redirect('auth/register')->withInput($input)->withErrors($validator);
		 }
		DB::connection('base')->transaction(function() use ($input)
		 {
		      $organizations = Organizations::create([
					'email' => $input['email'],
					'secondEmail' => $input['secondEmail'],
					'customerId' => "dumy".date('His'),
					'domain' => $input['domain']
				]);
				if($organizations)
				{
					$customerId = $this->generateCustomerId($input['domain'],$organizations->id);
					$organizations->update(['customerId'=>$customerId]);
					$orgInput  = array('customerId'=>$customerId,
									'orgName'=>$input['name'],
									'phone'=>$input['phone'],
									'phone1'=>$input['phone1']);

					$organizationInfo = new OrganizationInfo($orgInput);
					if($organizations->organizationInfo()->save($organizationInfo))
					{
						//create new data base
						if(DB::statement(DB::raw('CREATE DATABASE '.$customerId)))
						{
							Clients::create(['customerId'=>$customerId,'domain'=>$organizations->domain,
											'host'=>'localhost','username'=>'root','password'=>'password','database'=>$customerId,'driver'=>'myslq']);
							//create a dynamic connectoin to access new database
							configureConnection($customerId);
							//create tables in new database
							Artisan::call('migrate', array('--force' => true,'--database'=>$customerId,  '--path' => 'database/client'));
							//create admin user in new database
							$user = new User;
					        $user->setConnection($customerId);
						    $user->email = $input['email'];
							$user->userId = "dumy".date('His');
							$user->isAdmin = 1;
							$user->active = str_random(28).date('His');
							$user->password = bcrypt($input['password']);
							if($user->save())
							{
								$userId = $this->generateUserId($customerId,$user->id);
								$user->update(['userId'=>$userId]);
								$input  = array(
												//'name'=>$input['name'],
												//'dob'=>$data['dob'],
												//'gender'=>$data['gender'],
												'phone'=>$input['phone'],
												'created_by'=>$user->id,
						           				'updated_by'=>$user->id);
								$profile = new Profile($input);
								$user->profile()->save($profile);
							}
						}
					}
					
				}
		 });
		return redirect('auth/register')->with('message', 'Success');
	}
	public function generateCustomerId($domain,$id)
	{
		return 'ORG'.dechex($id).date('s');
	}
	public function generateUserId($customerId,$id)
	{
		return $customerId.'u'.$id;
	}
	public function loginGet()
	{
		return view('admin.login');
	}
	public function loginPost(Request $request)
	{
		/*if(DB::connection()->getDatabaseName())
		{
		   echo "conncted sucessfully to database ".DB::connection()->getDatabaseName();
		}
		 die;*/
		//print_r($request->all()); die;
		$input = $request->all();
		$verifier = App::make('validation.presence');
        $verifier->setConnection('base');
        $validatorRule =['email'=>'required|email',
            'password'=>'required'];
        $validator = Validator::make($input,$validatorRule);
        $validator->setPresenceVerifier($verifier);
		if ($validator->fails())
		{
			return redirect('auth/login')->withInput($input)->withErrors($validator);
		}
		$domain = Clients::where('domain','=',$this->getDomainFromEmail($input['email']))->first();
		if(!$domain)
		{
			$errors = $validator->messages();
		    $errors->add('email', 'Invalid credentials');
			return redirect('auth/login')->withInput($input)->withErrors($errors);
		}
		//print_r($domain->database); die;
		configureConnection($domain->database);
		DB::disconnect();
	    \Config::set('database.default',$domain->database);
	    DB::reconnect();
	    DB::connection($domain->database);
	    Session::put('blabla', "blabla");
		if (Auth::attempt(['email' => $input['email'], 'password' => $input['password'],'active'=>1]))
        {
        if(DB::connection()->getDatabaseName())
		{
		   echo "conncted sucessfully to database ".DB::connection()->getDatabaseName();
		}
		print_r(Auth::user());
		 //die;
           return redirect('/');
        }
        else
        {
        	$errors = $validator->messages();
		    $errors->add('email', 'Invalid credentials');
        	return redirect('auth/login')->withInput($input)->withErrors($errors);
        }


		/*$checkAdmin = Organization::where(['email'=> $input['email'],'active'=>1])->first();
		if($checkAdmin)
		{
			if (Hash::check('secret', $hashedPassword))
			{
			    // The passwords match...
			}
			echo "Dfvdfdfvdf"; die;
		}
		echo "dfvdf"; die;
		 if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']]))
        {
        	echo "yes"; die;
            return redirect()->intended('dashboard');
        }*/
	}
	public function getDomainFromEmail($email)
	{
	    // Get the data after the @ sign
	    $domain = substr(strrchr($email, "@"), 1);
	 
	    return $domain;
	}
}
