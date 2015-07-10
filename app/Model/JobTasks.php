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
	public function createdby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId','updated_by');
    }
    public function assigneeDetail()
    {   
        return $this->hasOne('App\Model\Profile', 'userId', 'assignee');
    }
    public function assignerDetail()
    {   
        return $this->hasOne('App\Model\Profile', 'userId','assigner');
    }
    public function comments()
    {
        return $this->hasMany('App\Model\JobTaskComments','taskId','id');
    }
	public static function validation($data)
    {
        $rule = array('title'=>'required',
            'description'=>'required|max:1000',
            'assignee'=>'required',
            'assigneeEmail' => 'email',
            'status' => 'in:Draft,Sent,Rejected,Open,Completed,Closed,Cancelled',
            'dueDate' => 'required');
        $validator = Validator::make($data,$rule);
        return $validator;
    }
}
