<?php namespace App\Services;

use App\User;
use App\Model\Profile;
use Auth;
use App;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use DB;
class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data,$userId=NULL)
	{
		//$verifier = App::make('validation.presence');
        //$verifier->setConnection('jotterBase');
		if($userId)
		{
			$validator = Validator::make($data, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users,email,'.$userId,
			'password' => 'confirmed|min:6',
			'phone'	=>'required|Regex:/^([0-9\s\-\+\(\)]*)$/',
			'dob' =>'required|date|date_format:Y-m-d|before:-15y',
			'department'=>'max:30',
			'gender' =>'required|in:M,F,O',
		]);
		}
		else
		{
			$validator = Validator::make($data, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
			'phone'	=>'required|Regex:/^([0-9\s\-\+\(\)]*)$/',
			'dob' =>'required|date|date_format:Y-m-d|before:-15y',
			'gender' =>'required|in:M,F,O',
			'department'=>'max:30',
		]);
		}
        //$validator->setPresenceVerifier($verifier);
        return $validator;
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		DB::transaction(function() use ($data)
		 {
		      $user = User::create([
					'email' => $data['email'],
					'userId' => "dumy".date('His'),
					'password' => bcrypt($data['password']),
				]);
				if($user)
				{
					$userId = $this->generatePublicUserId($user->id);
					$user->update(['userId'=>$userId]);
					$input  = array('userId'=>$user->id,
									'name'=>$data['name'],
									'dob'=>$data['dob'],
									'gender'=>$data['gender'],
									'phone'=>$data['phone']);

					$profile = new Profile($input);
			        //$profile->setConnection(env('GEN_DATABASE'));
					$user->profile()->save();
				}
		 });
		
	}
	public function generatePublicUserId($id)
	{
		//echo dechex(12345654); die;
		//genjo => GENERAL JOTTER
		return "GEN".dechex($id).date('s');
	}

}
