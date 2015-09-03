<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class Notification extends Eloquent
{
    protected $fillable   = ['userId','object_id', 'object_type', 'subject', 'body','is_read'];
    public function user()
    {
        return $this->belongsTo('App\User', 'userId', 'id');
    }
}
?>