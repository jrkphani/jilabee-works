<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
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
	protected $fillable = ['title', 'description','assignee','status','assigner','due','created_by','updated_by'];
	public function createdby()
    {	
        return $this->hasOne('App\User', 'id', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\User', 'id', 'updated_by');
    }
    public function minute()
    {
        return $this->hasOne('App\Model\Minutes', 'id', 'mhid');
    }
    public function comments()
    {
        return $this->hasMany('App\Model\Comments','nid','id');
    }
    public function getassignee()
    {   
        return $this->hasOne('App\User', 'id', 'assignee');
    }
    public function getassigner()
    {   
        return $this->hasOne('App\User', 'id', 'assigner');
    }
    public static function validation($data)
    {
        $rule = array('title'=>'required|max:64',
                        'description'=>'required|max:254',
                        'assignee'=>'required',
                        //  'assigner'=>'required',
                        'due'=>'required|date|date_format:Y-m-d|after:today');
        return Validator::make($data,$rule);

    }


}
