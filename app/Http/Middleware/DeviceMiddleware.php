<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceMiddleware
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
        $user = Auth::user();
        $currentDevice = hash('sha256', $request->header('User-Agent'));

        if ($user && $user->last_login_device !== $currentDevice) {
            Auth::logout();
            return redirect('/login')->withErrors(['Vous ne pouvez vous connecter que sur un seul périphérique à la fois.']);
        }
        return $next($request);
    }
}
