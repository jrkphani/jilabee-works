<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class Minuteshistory extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'minutes_history';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['mid','lock_flag','attendees','venue','created_by','updated_by'];
	public function minute()
    {	
        return $this->hasOne('App\Model\Minutes', 'id', 'mid');
    }
	public function createdby()
    {	
        return $this->hasOne('App\User', 'id', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\User', 'id', 'updated_by');
    }
    public function notes()
    {
        return $this->hasMany('App\Model\Notes','mhid','id');
    }
    public function notes_draft()
    {
        return $this->hasMany('App\Model\Notesdraft','mhid','id');
    }
    public static function validatoin($data)
    {
        $rule = array('attendees'=>'required','venue'=>'max:64');
        return Validator::make($data,$rule);
    }
}
