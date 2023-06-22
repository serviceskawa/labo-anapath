<?php

namespace App\Http\Middleware;

use App\Models\Setting;
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
        $setting = Setting::find(1);
        $now = Carbon::now();
        $currentTimeFormatted = $now->format('H:i:s');

        if ($currentTimeFormatted<$setting->begining_date || $now>$setting->ending_date) {
            if (! $request->expectsJson()) {
                return route('login');
            }
        }
    }
}
