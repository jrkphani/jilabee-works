<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
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
	protected $fillable = ['title','description','venue','attendees','minuters','status','reason','created_by','updated_by'];	
	public static function validation($data)
    {

        $rule = array('title'=>'required|max:64',
        	'description'=>'required',
            'venue'=>'max:64',
            //'attendees'=>'',
            'minuters' => 'required',
            'status' => 'in:waiting,rejected',
            'reason' => 'max:64');
        $validator = Validator::make($data,$rule);
        return $validator;
    }
    public function createdby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId','updated_by');
    }
}
