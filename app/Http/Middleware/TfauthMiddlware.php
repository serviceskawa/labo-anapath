<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;

class TfauthMiddlware
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
        // dd($request);
        $user = auth()->user(); // récupère l'utilisateur authentifié

        if ($user && $user->two_factor_enabled == 1) {
            // dd($user);
            return $next($request);
        }
        return redirect()->route('login.confirm');
    }
}
