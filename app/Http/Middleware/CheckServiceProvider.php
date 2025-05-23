<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckServiceProvider
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        if ($user && in_array($user->user_type, ['gardener', 'service_provider'])) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Unauthorized - Only gardeners and service providers can perform this action'
        ], 403);
    }
}