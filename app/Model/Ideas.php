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
	protected $fillable = ['title', 'description','orginators','created_by','updated_by'];
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
    public function orginator()
    {   
        return $this->hasOne('App\User', 'id', 'orginators');
    }

}
