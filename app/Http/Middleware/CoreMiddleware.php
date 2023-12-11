<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CoreMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Set the allowed domains to receive CORS requests
        // If the request origin is in the allowed domains, set the CORS headers
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        // // Otherwise, return an error response
        // return response('Unauthorized', 401);
    }
}
