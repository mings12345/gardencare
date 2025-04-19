<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',              // All API routes
        'broadcasting/auth',  // Pusher auth endpoint
        'sanctum/csrf-cookie' // Sanctum's CSRF cookie route
    ];
}
