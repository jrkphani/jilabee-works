<?php namespace App\Http\Middleware;

use Closure;
use Activity;
use Request;
class logAllActivity {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if(Request::method() == 'POST')
		{
			$inputs = Request::all();
			unset($inputs['_token']);
			unset($inputs['password']);
			unset($inputs['password_confirmation']);
			if($inputs)
			{
				$details = serialize($inputs);
			}
			else
			{
				$details = null;
			}
			Activity::log(['contentType'=>Request::path(),'action'=>Request::method(),'details'=>$details]);
		}
		return $next($request);
	}

}
