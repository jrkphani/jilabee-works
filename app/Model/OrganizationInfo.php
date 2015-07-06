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
	protected $connection = 'jotterBase';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['customerId','orgName','address','phone','phone1','licenses'];
}
