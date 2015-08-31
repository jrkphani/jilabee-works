<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
use Auth;
class TempMeetings extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tempMeetings';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['destails','created_by','updated_by'];

    public function createdby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId','updated_by');
    }
    public function requestedby()
    {   
        return $this->hasOne('App\Model\Profile', 'userId','updated_by');
    }
    public static function validation($data)
    {

        $rule = array('meetingTitle'=>'required|max:64',
            'meetingDescription'=>'required',
            'startDate'=>'required|date|before:tomorrow',
            'endDate'=>'required|date|after:startDate',
            'purpose'=>'max:64',
            'meetingType'=>'max:64',
            'venue'=>'max:64');
        $validator = Validator::make($data,$rule);
        return $validator;
    }
}
