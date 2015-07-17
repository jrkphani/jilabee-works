<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
use App;
class Organizations extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'organizations';
    // protected $connection = 'jotterBase';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['customerId','domain','email','secondEmail','password','active'];
    public static function validation($data)
    {
        //$verifier = App::make('validation.presence');

        //$verifier->setConnection('jotterBase');

        $rule = array('customerId'=>'unique:organizations',
            'name'=>'required',
            'domain'=>'required|min:3|unique:organizations',
            'email' => 'required|email|max:255|unique:users',
            'secondEmail' => 'email|max:255',
            'password' => 'required|confirmed|min:6',
            'phone' =>'required|Regex:/^([0-9\s\-\+\(\)]*)$/',
            'phone1' =>'Regex:/^([0-9\s\-\+\(\)]*)$/');
        $validator = Validator::make($data,$rule);
        //$validator->setPresenceVerifier($verifier);
        return $validator;
    }
    public function organizationInfo()
    {   
        return $this->hasOne('App\Model\OrganizationInfo', 'customerId', 'customerId');
    }
}
