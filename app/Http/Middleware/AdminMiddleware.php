<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->user_type === 'admin') {
            return $next($request);
        }

        return redirect('/')->with('error', 'You do not have access to this page.');
    }
}