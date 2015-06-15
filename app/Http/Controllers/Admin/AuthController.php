<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Organization;
use App\Model\OrganizationInfo;
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
		$validator = Organization::validation($request->all());
		$input = $request->all();
		 if ($validator->fails())
		 {
		 	return redirect('admin/register')->withInput($input)->withErrors($validator);
		 }
		DB::connection('base')->transaction(function() use ($input)
		 {
		      $organization = Organization::create([
					'email' => $input['email'],
					'customerId' => "dumy".date('His'),
					'domain' => $input['domain'],
					'password' => bcrypt($input['password']),
				]);
				if($organization)
				{
					$customerId = $this->generateCustomerId($input['domain'],$organization->id);
					$organization->update(['customerId'=>$customerId]);
					$input  = array('customerId'=>$customerId,
									'orgName'=>$input['name'],
									'phone'=>$input['phone'],
									'phone1'=>$input['phone1']);

					$organizationInfo = new OrganizationInfo($input);
					$organization->organizationInfo()->save($organizationInfo);
					return redirect('admin/register')->with('message', 'Success');
				}
		 });
		return redirect('admin/register')->withInput($input)->with('error', 'Oops something went wrong!');
	}
	public function generateCustomerId($domain,$id)
	{
		//return substr($domain, 0, 3).dechex($id);
		return 'ORG'.dechex($id).date('s');
	}
	public function loginGet()
	{
		$validator = $this->registrar->validator($request->all());

		if ($validator->fails())
		{
			$this->throwValidationException(
				$request, $validator
			);
		}
	}
	public function loginPost()
	{
		$validator = $this->registrar->validator($request->all());

		if ($validator->fails())
		{
			$this->throwValidationException(
				$request, $validator
			);
		}
	}
}
