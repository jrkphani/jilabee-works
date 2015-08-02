<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
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
	public function assignerDetail()
    {   
        return $this->hasOne('App\Model\Profile', 'userId','assigner');
    }
}
