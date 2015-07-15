<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class OtherTasks extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'otherTasks';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['taskId','type','title','description','org','created_by','updated_by'];
	public function assigneeDetail()
    {   
        return $this->hasOne('App\Model\Profile', 'userId', 'assignee');
    }
    public function comments()
    {
        return $this->hasMany('App\Model\OtherTaskComments','taskId','id');
    }
}
