<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class OrganizationInfo extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'organizationInfo';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['customerId','orgName','address','phone','phone1','licenses'];
	public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->setConnection('base');
    }
	/*public function organizationInfo()
    {   
        return $this->hasOne('App\Model\Organization', 'customerId', 'customerId');
    }*/
}
