<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Notesdraft extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'notes_draft';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['mhid','title', 'description','assignee','created_by'];
	public function createdby()
    {	
        return $this->hasOne('App\User', 'id', 'created_by');
    }

}
