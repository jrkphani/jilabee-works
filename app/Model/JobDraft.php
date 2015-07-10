<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class JobDraft extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'draftTasks';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title','description','assignee','assigner','notes','dueDate','created_by'];
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
}
