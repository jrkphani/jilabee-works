<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Notifications extends Model
{
	protected $table = 'notifications';
    protected $fillable   = ['userId','parentId','objectId', 'objectType','tag','subject', 'body','isRead'];
    public function user()
    {
        return $this->belongsTo('App\User', 'userId', 'id');
    }
    public function meeting()
    {
        return $this->hasOne('App\Model\Meetings', 'id', 'objectId');
    }
}
?>