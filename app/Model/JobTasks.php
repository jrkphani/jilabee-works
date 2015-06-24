<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class JobTasks extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'jobTasks';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title','description','assignee','assigner','status','dueDate','username','created_by','updated_by'];
	public static function validation($data)
    {
        $rule = array('title'=>'required',
            'description'=>'required|max:255',
            'assignee'=>'required',
            'assigneeEmail' => 'email',
            //'status' => '',
            'dueDate' => 'required');
        $validator = Validator::make($data,$rule);
        return $validator;
    }
}
