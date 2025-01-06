<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class AccessTimeMiddleware
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
        $setting = Setting::find(1);
        $now = Carbon::now();
        //Récupérer l'heure actuelle
        $currentTimeFormatted = $now->format('H:i:s');

        $message = "";
        if ($currentTimeFormatted<$setting->begining_date)
        {
            $message = "Une erreur s'est produite lors de la connexion";
        }elseif($currentTimeFormatted>$setting->ending_date)
        {
            $message = "Une erreur s'est produite lors de la connexion";
        }

        //Vérifier si l'heure actuelle est dans l'intervalle des heures de travail définies
        if ($currentTimeFormatted < $setting->begining_date || $currentTimeFormatted > $setting->ending_date) {
            if (!$request->user()->hasRole('accessHTime')) {
                return redirect('login')->withErrors([$message]);
            }
        }
        return $next($request);
    }
}
