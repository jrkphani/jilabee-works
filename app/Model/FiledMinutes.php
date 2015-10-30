<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class FiledMinutes extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'filedMinutes';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['taskId','minuteId','title','status','description','assignee','assigner','dueDate'];

	public function assigneeDetail()
    {   
        return $this->hasOne('App\Model\Profile', 'userId', 'assignee');
    }
    public function assignerDetail()
    {   
        return $this->hasOne('App\Model\Profile', 'userId','assigner');
    }
}
