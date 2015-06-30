<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
use Auth;
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
	protected $fillable = ['venue','attendees','absentees','minuteDate','lock_flag','created_by','updated_by'];
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
	public static function isMinuter($meetingId)
    {
    	//check permission as minuter
    	if($meeting = Meetings::where('id','=',$meetingId)->whereRaw('FIND_IN_SET("'.Auth::id().'",minuters)')->first())
    	{
    		return $meeting;
    	}
    	return FALSE;
    }
    public static function validation($data)
    {
        $rule = array('venue'=>'max:64',
            'attendees'=>'required|max:64',
            'minuteDate'=>'required');
        $validator = Validator::make($data,$rule);
        return $validator;
    }
}