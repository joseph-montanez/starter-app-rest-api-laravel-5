<?php namespace App\Http\Middleware;

class Token {

    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, $next)
    {

        $token = \App::make('user\Contracts\Token', [
            'token' => $request->input('token', $request->header('X-Token'))
        ]);

        $user = $token->getUser();

        if (!$user) {
            return \Response::make(['success' => false,  'message' => 'Unauthorized'], 401);
        } else {
            \Auth::setUser($user);
        }

        return $next($request);
    }

}