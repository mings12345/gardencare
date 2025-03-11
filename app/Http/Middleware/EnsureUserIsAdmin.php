<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and is an admin
        if (auth()->check() && auth()->user()->user_type === 'admin') {
            return $next($request);
        }

        // Redirect non-admin users to the home page or show an error
        return redirect('/')->with('error', 'You do not have access to this page.');
    }
}
