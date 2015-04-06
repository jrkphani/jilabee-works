<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class Noteshistory extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'notes_history';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['description','created_by','updated_by'];
	public function notes()
    {	
        return $this->hasOne('App\Model\Notes', 'id', 'nid');
    }
	public function createdby()
    {	
        return $this->hasOne('App\User', 'id', 'created_by');
    }
    public function updatedby()
    {	
        return $this->hasOne('App\User', 'id', 'updated_by');
    }
     public static function validation($data)
    {
        $rule = array( 'description'=>'required|max:254');
        return Validator::make($data,$rule);

    }
}
