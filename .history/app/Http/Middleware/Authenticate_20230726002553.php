<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request, Closure $next, $guard = null)
    
    {
        if ($this->auth->guard($guard)->guest()) {
            // If the user is not authenticated, redirect them to the login page.
            return redirect()->guest(route('login'));
        }

        return $next($request);
    }
}
