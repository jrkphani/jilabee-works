<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
class Adminonly {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}
	public function handle($request, Closure $next)
	{
		if ($this->auth->check())
		{
			if($this->auth->user()->isAdmin == '1')
			{
				return $next($request);	
			}
			else
			{
				return response('Unauthorized.', 401);
			}
			
		}
		else
		{
			if ($request->ajax())
			{
				return response('Unauthorized.', 401);
			}
			else
			{
				return redirect('admin/auth/login');
			}
		}

		
		// if(Auth::check())
		// {
		// 	print_r(Auth::user()->emai); die;
		// 	//return $next($request);
		// }
		// else
		// {
			
		// }
	}

}