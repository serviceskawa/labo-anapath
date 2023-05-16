<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // $this->middleware('tfauth');
    }

    public function tfauth()
    {
        $code = rand(100000,999999);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //Check if account's user is active

        if (!$user->is_active) {
            // Déconnectez l'utilisateur
            Auth::logout();

            // Redirigez l'utilisateur vers la page de connexion avec un message d'erreur
            return redirect()->route('login')->with('error', 'Votre compte est désactivé. Veuillez contacter l\'administrateur.');
        }
        
        //update attribute is_connect pour savoir qui est en ligne
        $user->fill([
            'is_connect' => 1,
        ])->save();
        return redirect()->route('login.confirm');
    }

    public function logout(Request $request)
    {
            // récupère l'utilisateur actuel
            $userConnect = Auth::user();
            $user = (new User)->findorfail($userConnect->id);
            $user->is_connect = 0;
            $user->two_factor_enabled =0;
            $user->save();
            Auth::logout(); // déconnecte l'utilisateur
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login');
    }

}
