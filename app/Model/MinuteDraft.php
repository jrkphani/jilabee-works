<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class MinuteDraft extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'draftMinutes';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title','description','assignee','assigner','orginator','dueDate','type','created_by'];
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
}
