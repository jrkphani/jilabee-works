<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class MinuteTaskComments extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'minuteTaskComments';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['description','created_by','updated_by'];
	public function createdby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId','updated_by');
    }
    public function task()
    {
        return $this->hasOne('App\Model\JobTasks', 'id', 'taskId');
    }
    public static function validation($data)
    {
        $rule = array(
            'description'=>'required|max:64');
        $validator = Validator::make($data,$rule);
        return $validator;
    }
}
