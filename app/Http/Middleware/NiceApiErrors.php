<?php namespace App\Http\Middleware;

use Closure;

class NiceApiErrors {

	/**
	 * After Handler
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		/* TODO catch errors and present as json instead? */
        $response = $next($request);
		//dd($request->header('Accept'));
        return $response;
	}

}
