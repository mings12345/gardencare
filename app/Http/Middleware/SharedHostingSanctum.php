<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class SharedHostingSanctum extends Middleware
{
    protected function authenticate($request, array $guards)
    {
        $token = $request->bearerToken() ?: $request->cookie('__token');
        
        if ($token) {
            $request->headers->set('Authorization', 'Bearer '.$token);
        }

        parent::authenticate($request, $guards);
    }
}
