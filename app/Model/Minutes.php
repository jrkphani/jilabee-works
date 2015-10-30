<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Auth;
class Minutes extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    use SoftDeletes;
	protected $table = 'minutes';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['venue','attendees','absentees','endDate','startDate','filed','created_by','updated_by'];
	public function createdby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId','updated_by');
    }
     public function meeting()
    {   
        return $this->hasOne('App\Model\Meetings', 'id', 'meetingId');
    }
    public function draft()
    {
        return $this->hasMany('App\Model\MinuteDraft','minuteId','id');
    }
    public function tasks()
    {
        return $this->hasMany('App\Model\MinuteTasks','minuteId','id');
    }
    public function file()
    {
        return $this->hasMany('App\Model\FiledMinutes','minuteId','id');
    }
    public function ideas()
    {
        return $this->hasMany('App\Model\Ideas','minuteId','id');
    }
    public static function validation($data)
    {
        $rule = array('venue'=>'max:64',
            'attendees'=>'required|max:64',
            'startDate'=>'required|date|before:today',
            'endDate'=>'date|after:startDate',);
        $validator = Validator::make($data,$rule);
        return $validator;
    }
}
