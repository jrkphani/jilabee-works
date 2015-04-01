<?php namespace App\Http\Middleware;

use Closure;
use Auth;
class Adminonly {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (Auth::user()->profile->role != '999')
		{
			abort(403,'Invalid Access');
		}
		return $next($request);
	}

}
