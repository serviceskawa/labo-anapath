<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BranchRequired
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
        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Routes qui n'ont pas besoin de sélection de branche
        $exemptRoutes = [
            'login',
            'login.confirm',
            'login.postAuth',
            'logout',
            'select.branch',
            'store.branch',
            'password.request',
            'password.email',
            'password.reset',
            'password.update',
            'register' // si vous avez une page d'inscription
        ];

        // Patterns d'URL à exempter (pour les assets, API, etc.)
        $exemptPatterns = [
            'api/*',
            'storage/*',
            'assets/*',
            'css/*',
            'js/*',
            'images/*'
        ];

        $currentRouteName = $request->route() ? $request->route()->getName() : null;
        $currentPath = $request->path();

        // Vérifier si la route actuelle est exemptée
        if ($currentRouteName && in_array($currentRouteName, $exemptRoutes)) {
            return $next($request);
        }

        // Vérifier les patterns d'URL exemptés
        foreach ($exemptPatterns as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        // Vérifier que l'utilisateur a passé la 2FA
        if (!Session::has('user_2fa') || Session::get('user_2fa') != Auth::id()) {
            // Nettoyer la session et rediriger vers login
            Session::forget(['user_2fa', 'selected_branch_id', 'selected_branch_name']);
            return redirect()->route('login')->withErrors(['Votre session a expiré. Veuillez vous reconnecter.']);
        }

        // Vérifier qu'une branche est sélectionnée
        if (!Session::has('selected_branch_id') || empty(Session::get('selected_branch_id'))) {
            return redirect()->route('select.branch')->with('warning', 'Vous devez sélectionner une branche pour continuer.');
        }

        // Vérification supplémentaire : s'assurer que la branche existe toujours et que l'utilisateur y a accès
        $branchId = Session::get('selected_branch_id');
        $userId = Auth::id();

        $branchAccess = DB::table('branch_user')
            ->join('branches', 'branch_user.branch_id', '=', 'branches.id')
            ->where('branch_user.user_id', $userId)
            ->where('branch_user.branch_id', $branchId)
            ->where('branch_user.is_default', 1) // Assumer qu'il y a une colonne is_active
            ->whereNull('branch_user.deleted_at')
            ->exists();

        if (!$branchAccess) {
            // La branche n'existe plus ou l'utilisateur n'y a plus accès
            Session::forget(['selected_branch_id', 'selected_branch_name']);
            return redirect()->route('select.branch')->withErrors(['Votre accès à cette branche a été révoqué. Veuillez en sélectionner une autre.']);
        }

        // Ajouter les informations de branche dans la requête pour usage ultérieur
        $request->attributes->set('current_branch_id', $branchId);
        $request->attributes->set('current_branch_name', Session::get('selected_branch_name'));

        return $next($request);
    }
}
