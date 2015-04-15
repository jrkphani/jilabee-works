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
	protected $fillable = ['mid','lock_flag','attendees','absentees','venue','dt','created_by','updated_by'];
	public function meeting()
    {	
        return $this->hasOne('App\Model\Meetings', 'id', 'mid');
    }
	public function createdby()
    {	
        return $this->hasOne('App\User', 'id', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\User', 'id', 'updated_by');
    }
    public function tasks()
    {
        return $this->hasMany('App\Model\Tasks','mhid','id');
    }
    public function ideas()
    {
        return $this->hasMany('App\Model\Ideas','mhid','id');
    }
    public function tasks_draft()
    {
        return $this->hasMany('App\Model\Tasksdraft','mhid','id');
    }
    public static function validation($data)
    {
        $messages = array(
            'dt.required' => 'The date time field is required.',
            'dt.date_format' => 'The date time does not match the format Y-m-d H:i:s.',
            'dt.before' => 'The date time must be before today date and current time.',
            'dt.date' => 'The date time is not a valid.'
        );
        $rule = array('attendees'=>'required',
                    'venue'=>'required:max:64',
                    'dt'=>'required|date|date_format:Y-m-d H:i:s|before:now');
        return Validator::make($data,$rule,$messages);
    }
}
