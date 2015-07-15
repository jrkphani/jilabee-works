<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class OtherTaskComments extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'otherTaskComments';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['description','created_by','updated_by'];
    public function task()
    {
        return $this->hasOne('App\Model\OtherTasks', 'id', 'taskId');
    }
    public static function validation($data)
    {
        $rule = array(
            'description'=>'required|max:64');
        $validator = Validator::make($data,$rule);
        return $validator;
    }
}
