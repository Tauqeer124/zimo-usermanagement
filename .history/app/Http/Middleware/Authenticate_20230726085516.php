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
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Check if the user is logged in
            if (! $request->user()) {
                dd
                // User is not logged in, redirect to the login page
                return route('login');
            } else {
                dd("kkk");
                // User is logged in, redirect to the dashboard page
                return route('dashboard'); // Assuming 'dashboard' is the name of the route for the dashboard page
            }
        }
    }
}
