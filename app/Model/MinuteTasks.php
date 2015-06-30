<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class MinuteTasks extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'minuteTasks';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title','description','assignee','assigner','dueDate','created_by','updated_by'];
	public function createdby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId','updated_by');
    }
    public function minute()
    {
        return $this->hasOne('App\Model\Minutes', 'id', 'minuteId');
    }
    public static function validation($data)
    {
        $rule = array('title'=>'required',
            'description'=>'required|max:64',
            'assignee'=>'required',
            'assigneeEmail' => 'email',
            //'status' => '',
            'dueDate' => 'required');
        $validator = Validator::make($data,$rule);
        return $validator;
    }
}