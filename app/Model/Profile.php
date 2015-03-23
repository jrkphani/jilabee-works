<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Profile extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_profiles';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['uid', 'phone','dob','created_by','updated_by'];
	public function createdby()
    {	
        return $this->hasOne('App\User', 'id', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\User', 'id', 'updated_by');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'uid', 'id');
    }

}
