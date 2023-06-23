<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
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
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {

            //update attribute is_connect pour savoir qui est en ligne
            return redirect()->route('login.confirm');
        }

        return redirect("login")->withErrors(['Oppes! Vous aviez entré des données invalides']);
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

        //Récupérer les rôles de l'utilisateur qui se connecte
        // $roles = getRolesByUser($user->id);
        // $setting = Setting::find(1);
        //     $now = Carbon::now();
        //     //Récupérer l'heure actuelle
        //     $currentTimeFormatted = $now->format('H:i:s');

        //     //Vérifier si l'heure actuelle est dans l'intervalle des heures de travail définies
        //     if ($currentTimeFormatted<$setting->begining_date || $currentTimeFormatted>$setting->ending_date) {
        //         $access = false;
        //         foreach ($roles as $key => $role) {
        //             //Lorsque l'utilisateur n'a pas le role nécessaire.
        //             if ($role->name == "accessHTime") {
        //                 $access = true;
        //                 break;
        //             }else{
        //                 continue;
        //             }
        //         }

        //         if(!$access) {
        //             Auth::logout();
        //             // Redirigez l'utilisateur vers la page de connexion avec un message d'erreur
        //             // return redirect()->route('login')->with('error', 'Vous n\'aviez plus access à la plateforme. Veuillez contacter l\'administrateur.');
        //             return redirect('/login')->withErrors(['Vous n\'aviez plus access à la plateforme. Veuillez contacter l\'administrateur.']);
        //         }
        //     }

        //     //Check if account's user is active
        // if (!$user->is_active) {
        //     // Déconnectez l'utilisateur
        //     Auth::logout();
        //     // Redirigez l'utilisateur vers la page de connexion avec un message d'erreur
        //     return redirect()->route('login')->with('error', 'Votre compte est désactivé. Veuillez contacter l\'administrateur.');
        // }


        $user->lastlogindevice = hash('sha256', $request->header('User-Agent'));
        $user->save();
        $user->fill([
            'is_connect' => 1,
        ])->save();

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
