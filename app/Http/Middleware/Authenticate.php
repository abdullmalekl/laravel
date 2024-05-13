<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
    // we add handle function to save cookie from request and set it to header Authorization
    //  in backend so we do not need it in front-end
    public function handle($request, Closure $next, ...$guards)
    {
        if($jwt = $request->cookie('jwt')){
         $request->headers->set('Authorization' , 'Bearer '. $jwt);
        }
        $this->authenticate($request, $guards);

        return $next($request);
    }
}
