<?php namespace App\Services;

use App\User;
use App\Model\Profile;
use Auth;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

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
			'role'	=>'required|integer',
			'phone'	=>'Regex:/^([0-9\s\-\+\(\)]*)$/',
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
		$user = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
		]);
		if($user)
		{
			$input  = array('dob'=>$data['dob'],
							'gender'=>$data['gender'],
							'phone'=>$data['phone'],
							'created_by'=>Auth::user()->id,
              				'updated_by'=>Auth::user()->id);

			$profile = new Profile($input);
			$user->profile()->save($profile);
		}
	}

}
