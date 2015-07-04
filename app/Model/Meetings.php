<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
use Auth;
class Meetings extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'meetings';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title','description','venue','attendees','minuters','created_by','updated_by'];
	
	public function minutes()
    {
        return $this->hasMany('App\Model\Minutes','meetingId','id');
    }
    public function createdby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId','updated_by');
    }
	public function isMinuter()
    {
    	//check permission as minuter
    	if($this->whereRaw('FIND_IN_SET("'.Auth::id().'",minuters)')->first())
    	{
    		return $this;
    	}
    	return FALSE;
    }
}
