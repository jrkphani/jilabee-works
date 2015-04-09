<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Tasksdraft extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tasks_draft';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['mhid','title','due', 'description','assignee','assigner','created_by'];
	public function createdby()
    {	
        return $this->hasOne('App\User', 'id', 'created_by');
    }

}
