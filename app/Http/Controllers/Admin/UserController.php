<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Profile;
use Illuminate\Contracts\Auth\Guard;
use DB;
use Auth;
use Validator;
use Illuminate\Http\Request;
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
		return view('admin.addUser');
	}
	public function postAdd(Request $request)
	{
		$input = $request->all();
		$input['dbconnection'] = $request->session()->get('database');
		$validator = $this->registrar->validator($request->all());

		if ($validator->fails())
		{
			return redirect('/admin/user/add')->withInput($input)->withErrors($validator);
		}
		try
		 {
		 	DB::connection('base')->beginTransaction();
		    $user = new User();
			$user->setConnection('base');
			$user->email = $input['email'];
			$user->userId = "dumy".date('His');
			$user->password = bcrypt($input['password']);
			if($user->save())
			{
				DB::connection('base')->commit();
				$userId = generateUserId($input['dbconnection'],$user->id);
				$user->update(['userId'=>$userId]);
				$profile = new Profile();
				$profile->setConnection($input['dbconnection']);
				$profile->userId = $userId;
				$profile->name = $input['name'];
				$profile->dob = $input['dob'];
				$profile->gender = $input['gender'];
				$profile->phone = $input['phone'];
				$profile->created_by = Auth::id();
				$profile->updated_by = Auth::id();
				if(!$profile->save())
				{
					DB::connection('base')->rollback();
				}
				return redirect('/admin/user/add')->with('message','Success');
			}
		 }
		 catch (Exception $e)
		 {
	        //error
	        DB::connection('base')->rollback();
    	}	
	}
}