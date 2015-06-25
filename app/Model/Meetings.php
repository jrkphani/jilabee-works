<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class Meetings extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'meetings';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title','description','venue','attendees','minuters','created_by','updated_by'];	
}
