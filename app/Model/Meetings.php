<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Auth;
class Meetings extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    use SoftDeletes;
	protected $table = 'meetings';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title','description','venue','attendees','minuters','active','approved','requested_by','oid','created_by','updated_by'];
	
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
    public function requestedby()
    {   
        return $this->hasOne('App\Model\Profile', 'userId','updated_by');
    }
	public function isMinuter()
    {
        //check permission as minuter
        if(in_array(Auth::id(), explode(',',$this->minuters)))
         {
             return $this;
         }
         return FALSE;
    }
    public static function validation($data)
    {

        $rule = array('title'=>'required|max:64',
            'description'=>'required',
            'venue'=>'max:64',
            'participants' => 'required',
            'roles' => 'required',
            'approved' => 'in:0,1',
            'reason' => 'max:64');
        $validator = Validator::make($data,$rule);
        return $validator;
    }
}
