<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Organizations;
use App\Model\OrganizationInfo;
use App\Model\Clients;
use Auth;
use DB;
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
					$input  = array('customerId'=>$customerId,
									'orgName'=>$input['name'],
									'phone'=>$input['phone'],
									'phone1'=>$input['phone1']);

					$organizationInfo = new OrganizationInfo($input);
					if($organizations->organizationInfo()->save($organizationInfo))
					{
						//sdc;
						//'password' => bcrypt($input['password']

						if(DB::statement(DB::raw('CREATE DATABASE '.$customerId)))
						{
							Clients::create(['customerId'=>$customerId,'domain'=>$organizations->domain,
											'dbIp'=>'localhost','dbUser'=>'root','dbPassword'=>'password','dbName'=>$customerId]);
							echo "yes"; die;
						}
					}
					return redirect('auth/register')->with('message', 'Success');
				}
		 });
		return redirect('auth/register')->withInput($input)->with('error', 'Oops something went wrong!');
	}
	public function generateCustomerId($domain,$id)
	{
		return 'ORG'.dechex($id).date('s');
	}
	public function loginGet()
	{
		return view('admin.login');
	}
	public function loginPost(Request $request)
	{
		if(DB::connection()->getDatabaseName())
		{
		  // echo "conncted sucessfully to database ".DB::connection()->getDatabaseName();
		}
		 //die;
		//print_r($request->all()); die;
		$input = $request->all();

		if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']]))
        {
        	echo "done";
            return redirect('admin/');
        }
        else
        {
        	return redirect('admin/login');	
        }
echo "jjjjjjjjj";
        if(DB::connection()->getDatabaseName())
		{
		   echo "conncted sucessfully to database ".DB::connection()->getDatabaseName();
		}
		 die;


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
}
