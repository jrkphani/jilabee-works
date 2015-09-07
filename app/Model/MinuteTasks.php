<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
class MinuteTasks extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    use SoftDeletes;
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
    public function assigneeDetail()
    {   
        return $this->hasOne('App\Model\Profile', 'userId', 'assignee');
    }
    public function assignerDetail()
    {   
        return $this->hasOne('App\Model\Profile', 'userId','assigner');
    }
    public function minute()
    {
        return $this->hasOne('App\Model\Minutes', 'id', 'minuteId');
    }
    public function file()
    {
        return $this->hasMany('App\Model\FiledMinutes','taskId','id');
    }
    public function comments()
    {
        return $this->hasMany('App\Model\MinuteTaskComments','taskId','id');
    }
    public static function validation($data)
    {
        $rule = array('title'=>'required',
            'description'=>'required|max:1000',
            'assignee'=>'required',
            'status' => 'in:Draft,Sent,Rejected,Open,Completed,Closed,Cancelled',
            'dueDate' => 'required|date|after:today');
        $validator = Validator::make($data,$rule);
        return $validator;
    }
}
