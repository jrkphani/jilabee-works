<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Auth;
use Session;
use Activity;
use App\Model\Organizations;
use Validator;
class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;
		$this->middleware('guest', ['except' => ['getLogout']]);
	}
	public function getRegister()
	{
		//die;
		//return view('auth.register');
		return redirect('admin/auth/register');
	}
	public function postRegister(Request $request)
	{
		$validator = $this->registrar->validator($request->all());
		$validator->after(function($validator) use ($request)
		{
			if($domain = getDomainFromEmail($request->email))
			{
				if(Organizations::where('domain','=',$domain)->first())
				{
					$validator->errors()->add('email', 'Domain registered, Please contact domain admin');
				}
			}
		});
		if ($validator->fails())
		{
			if (strpos($validator->errors()->first('email'),'registered') !== false)
		 	{
			    $admin = Organizations::where('domain','=',getDomainFromEmail($request->email))->first();
			    sendEmail($admin->email,$admin->domain,'User Registration','emails.domainUser',['email'=>$request->email]);
			}
			$this->throwValidationException(
				$request, $validator
			);
		}
		$this->registrar->create($request->all());
		Activity::log([
		    'contentType' => 'User',
		    'action'      => 'General Signup',
		    'description' => 'General signup',
		    'details'     => 'Username: '.$request->name.'Email:'.$request->email
		]);
		//return redirect('/auth/register')->with('message', 'Registration successfully!');
		return redirect('admin/auth/register')->with('message', 'Registration success, please check your email for activation.');
	}
	public function postLogin(Request $request)
    {
    	$input = $request->all();
        $validatorRule =['email'=>'required|email',
            'password'=>'required'];
        $validator = Validator::make($input,$validatorRule);
		if ($validator->fails())
		{
			return redirect('auth/login')->withInput($input)->withErrors($validator);
		}
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password,'active'=>'1']))
        {
            // Authentication passed...
            if(starts_with(Auth::user()->userId, 'GEN'))
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
            }
            return redirect()->intended('/');
        }
        else
        {
        	$errors = $validator->messages();
			$errors->add('email', 'Invalid credentials');
        	return redirect('auth/login')->withInput($input)->withErrors($validator);
        }
    }

}