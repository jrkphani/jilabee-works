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
	protected $fillable = ['title','venue','attendees','minuters','created_by','updated_by'];
	public function createdby()
    {	
        return $this->hasOne('App\User', 'id', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\User', 'id', 'updated_by');
    }
    public function minutes()
    {
        return $this->hasMany('App\Model\Minutes','mid','id');
    }
    public static function validation($data)
    {
        $rule = array('title'=>'required',
                        'minuters'=>'required',
                        'attendees'=>'required',
                        'venue'=>'max:64','label'=>'max:8');
        return Validator::make($data,$rule);

    }
     public function hasPermissoin()
    {
        if(Auth::user()->profile->role == '999')
        {
            return TRUE;
        }
        else
        {
            if(in_array(Auth::user()->id, explode(',',$this->minuters)))
            {
                return TRUE;
            }
            return FALSE;
        }
    }

}
