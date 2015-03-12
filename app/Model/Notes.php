<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Notes extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'notes';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title', 'description','assignee','created_by','updated_by'];
	public function createdby()
    {	
        return $this->hasOne('App\User', 'id', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\User', 'id', 'updated_by');
    }
    public function minute_history()
    {
        return $this->hasOne('App\Model\Minuteshistory', 'id', 'mhid');
    }
    public function notes_history()
    {
        return $this->hasMany('App\Model\Noteshistory','nid','id');
    }

}
