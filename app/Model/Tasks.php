<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Tasks extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tasks';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	public function assignerDetail()
    {   
        return $this->hasOne('App\Model\Profile', 'userId','assigner');
    }
    public function assigneeDetail()
    {   
        return $this->hasOne('App\Model\Profile', 'userId','assignee');
    }
    public function minute()
    {
        return $this->hasOne('App\Model\Minutes', 'id', 'minuteId');
    }
    public function meeting()
    {
        return $this->hasOne('App\Model\Meetings', 'id', 'meetingId');
    }
}
