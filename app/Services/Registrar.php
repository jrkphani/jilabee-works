<?php namespace App\Services;

use App\User;
use App\Model\Profile;
use Auth;
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
	public function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
			'phone'	=>'required|Regex:/^([0-9\s\-\+\(\)]*)$/',
			'dob' =>'required|date|date_format:Y-m-d|before:-15y',
			'gender' =>'required|in:M,F,O',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		DB::transaction(function($data) use ($data)
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
					$input  = array('userId'=>$userId,
									'name'=>$data['name'],
									'dob'=>$data['dob'],
									'gender'=>$data['gender'],
									'phone'=>$data['phone'],
									'created_by'=>$user->id,
		              				'updated_by'=>$user->id);

					$profile = new Profile($input);
					$user->profile()->save($profile);
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
