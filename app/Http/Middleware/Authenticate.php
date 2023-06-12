<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
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
        $beging = Carbon::createFromTime(7,0,0);
        $end = Carbon::createFromTime(18,0,0);
        $now = Carbon::now();

        if ($now<$beging || $now>$end) {
            if (! $request->expectsJson()) {
                return route('login');
            }
        }
    }
}
