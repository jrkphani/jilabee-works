<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
use App;
class Organization extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'organization';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['customerId','domain','email','password','active'];
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->setConnection('base');
    }
    public static function validation($data)
    {
        $verifier = App::make('validation.presence');

        $verifier->setConnection('base');

        $rule = array('customerId'=>'unique:organization',
            'name'=>'required',
            'domain'=>'required|min:3|unique:organization',
            'email' => 'required|email|max:255|unique:organization',
            'password' => 'required|confirmed|min:6',
            'phone' =>'required|Regex:/^([0-9\s\-\+\(\)]*)$/',
            'phone1' =>'Regex:/^([0-9\s\-\+\(\)]*)$/');
        $validator = Validator::make($data,$rule);
        $validator->setPresenceVerifier($verifier);
        return $validator;
    }
    public function organizationInfo()
    {   
        return $this->hasOne('App\Model\OrganizationInfo', 'customerId', 'customerId');
    }
}
