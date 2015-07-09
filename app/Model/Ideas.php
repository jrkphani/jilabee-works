<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class Ideas extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ideas';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title','description','orginator','created_by','updated_by'];
	public function createdby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\Model\Profile', 'userId','updated_by');
    }
    public function orginatorDetail()
    {   
        return $this->hasOne('App\Model\Profile', 'userId', 'orginator');
    }
    public function minute()
    {
        return $this->hasOne('App\Model\Minutes', 'id', 'minuteId');
    }
    public static function validation($data)
    {
        $rule = array('title'=>'required',
            'description'=>'required|max:1000',
            //'orginator'=>'required'
            );
        $validator = Validator::make($data,$rule);
        return $validator;
    }
}
