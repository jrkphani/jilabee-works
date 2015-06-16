<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Validator;
class Clients extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'clients';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['domain','customerId','driver','database','database','password','username'];
	public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->setConnection('base');
    }
}
