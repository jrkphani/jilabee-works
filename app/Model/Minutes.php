<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class Minutes extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'minutes';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title', 'label','venue','attendees','created_by','updated_by'];
	public function createdby()
    {	
        return $this->hasOne('App\User', 'id', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\User', 'id', 'updated_by');
    }
    public function minute_history()
    {
        return $this->hasMany('App\Model\Minuteshistory','mid','id');
    }
    public static function validatoin($data)
    {
        $rule = array('title'=>'required','attendees'=>'required','venue'=>'max:64','label'=>'max:8');
        return Validator::make($data,$rule);
    }

}
