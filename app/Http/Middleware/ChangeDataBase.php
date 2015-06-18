<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use DB;
class ChangeDataBase {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($this->auth->check())
		{
			// $config = App::make('config');

   //  // Will contain the array of connections that appear in our database config file.
   //  $connections = $config->get('database.connections');

   //  print_r($connections); die;
			if ($request->session()->has('database'))
			{
				configureConnection($request->session()->get('database'));
				DB::disconnect();
			    \Config::set('database.default',$request->session()->get('database'));
			    DB::reconnect();
				//print_r($request->session()->get('database'));
    			//echo "efdfv"; die;
			}
			else
			{
				DB::disconnect();
			    \Config::set('database.default','client');
			    DB::reconnect();
			}
			//$this->auth->user()->
			//return new RedirectResponse(url('/admin'));
		}

		return $next($request);
	}

}
