<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Notifications extends Model
{
	protected $table = 'notifications';
    protected $fillable   = ['userId','objectId', 'objectType', 'subject', 'body','isRead'];
    public function user()
    {
        return $this->belongsTo('App\User', 'userId', 'id');
    }
}
?>