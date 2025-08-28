<?php

namespace App\Http\Controllers;

use App\Mail\TFAuthNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class TFAuthController extends Controller
{
    protected $user;
    protected $userConnect;
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->userConnect = auth()->user();
    }

    public function show()
    {
        $user = $this->userConnect;
        if ($user->is_active == 0) {
            return redirect()->route('login')->with('error', 'Votre compte est désactivé. Veuillez contacter l\'administrateur.');
        } else {
            $userEmail = $user->email;
            $this->sendEmail($user);
            $error = "";
            return view('auth.tfauth', compact('user', 'error'));
        }
    }

    // public function postAuth(Request $request)
    // {

    //     $user = $this->user->findorfail($this->userConnect->id);
    //     if ($request->code == $this->caesar_decipher_int($user->opt, 3)) {
    //         $user->two_factor_enabled = 1;
    //         $user->opt = NULL;
    //         $user->save();
    //         Session::put('user_2fa', auth()->user()->id);
    //         return response()->json('200');
    //     } else {
    //         return response()->json(['error' => 'Le code saisi est incorrecte']);
    //     }
    // }

    public function postAuth(Request $request)
    {
        $user = $this->user->findorfail($this->userConnect->id);
        if ($request->code == $this->caesar_decipher_int($user->opt, 3)) {
            $user->two_factor_enabled = 1;
            $user->opt = NULL;
            $user->save();
            Session::put('user_2fa', auth()->user()->id);

            // REDIRECTION VERS LA SÉLECTION DE BRANCHE AU LIEU DU DASHBOARD
            return response()->json('branch_select');
        } else {
            return response()->json(['error' => 'Le code saisi est incorrecte']);
        }
    }

    public function sendEmail(User $user)
    {
        $opt = rand(100000, 999999);
        $user = $user->findorfail($this->userConnect->id);
        $user->opt = $this->caesar_cipher_int($opt, 3);
        $user->save();

        $data = [
            'name' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'opt' => $this->caesar_decipher_int($user->opt, 3),
        ];
        #3. Envoi du mail
        try {
            Mail::to($user->email)->queue(new TFAuthNotification($data));
        } catch (\Throwable $ex) {
            dd($ex->getMessage());
        }
    }

    /**
     * Fonction pour chiffrer un entier à 6 chiffres en utilisant l'algorithme de César
     * avec un décalage (shift) spécifié.
     *
     * @param int $number - L'entier à chiffrer.
     * @param int $shift - Le décalage (shift) à utiliser pour chiffrer l'entier.
     * @return int - L'entier chiffré.
     */
    function caesar_cipher_int($number, $shift)
    {
        // Formatte l'entier avec des zéros à gauche pour avoir 6 chiffres.
        $message = sprintf("%06d", $number);

        // Initialise une variable pour stocker le message chiffré.
        $ciphered_message = "";

        // Boucle à travers chaque chiffre de l'entier.
        $length = strlen($message);
        for ($i = 0; $i < $length; $i++) {
            // Obtient le code ASCII du chiffre.
            $char = ord($message[$i]);

            // Applique le décalage (shift) pour obtenir le nouveau code ASCII chiffré.
            $cipher_char = chr((($char - 48 + $shift) % 10) + 48);

            // Ajoute le chiffre chiffré à la fin du message chiffré.
            $ciphered_message .= $cipher_char;
        }

        // Retourne l'entier chiffré.
        return intval($ciphered_message);
    }

    /**
     * Fonction pour déchiffrer un entier à 6 chiffres chiffré en utilisant l'algorithme de César
     * avec un décalage (shift) spécifié.
     *
     * @param int $ciphered_number - L'entier chiffré à déchiffrer.
     * @param int $shift - Le décalage (shift) à utiliser pour déchiffrer l'entier.
     * @return int - L'entier déchiffré.
     */
    function caesar_decipher_int($ciphered_number, $shift)
    {
        // Formatte l'entier chiffré avec des zéros à gauche pour avoir 6 chiffres.
        $ciphered_message = sprintf("%06d", $ciphered_number);

        // Initialise une variable pour stocker le message déchiffré.
        $deciphered_message = "";

        // Boucle à travers chaque chiffre de l'entier chiffré.
        $length = strlen($ciphered_message);
        for ($i = 0; $i < $length; $i++) {
            // Obtient le code ASCII du chiffre chiffré.
            $char = ord($ciphered_message[$i]);

            // Applique le décalage (shift) négatif pour obtenir le nouveau code ASCII déchiffré.
            $decipher_char = chr(((($char - 48) - $shift + 10) % 10) + 48);

            // Ajoute le chiffre déchiffré à la fin du message déchiffré.
            $deciphered_message .= $decipher_char;
        }

        // Retourne l'entier déchiffré.
        return intval($deciphered_message);
    }

    // NOUVELLE MÉTHODE : Afficher la page de sélection des branches
    public function selectBranch()
    {
        // Vérifier que l'utilisateur a passé la 2FA
        if (!Session::has('user_2fa') || Session::get('user_2fa') != auth()->id()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Récupérer les branches de l'utilisateur
        $branches = DB::table('branch_user')
            ->join('branches', 'branch_user.branch_id', '=', 'branches.id')
            ->where('branch_user.user_id', $user->id)
            ->select('branches.*', 'branch_user.is_default')
            ->orderBy('branch_user.is_default', 'desc') // Les branches par défaut en premier
            ->get();

        // Si l'utilisateur n'a aucune branche
        if ($branches->isEmpty()) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['Aucune branche n\'est assignée à votre compte. Contactez l\'administrateur.']);
        }

        // Si l'utilisateur n'a qu'une seule branche, la sélectionner automatiquement
        if ($branches->count() == 1) {
            Session::put('selected_branch_id', $branches->first()->id);
            Session::put('selected_branch_name', $branches->first()->name);
            return redirect('/home');
        }

        return view('auth.select-branch', compact('user', 'branches'));
    }

    // NOUVELLE MÉTHODE : Enregistrer la branche sélectionnée
    public function storeBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:branches,id'
        ]);

        // Vérifier que l'utilisateur a passé la 2FA
        if (!Session::has('user_2fa') || Session::get('user_2fa') != auth()->id()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Vérifier que l'utilisateur a accès à cette branche
        $branchExists = DB::table('branch_user')
            ->where('user_id', $user->id)
            ->where('branch_id', $request->branch_id)
            ->exists();

        if (!$branchExists) {
            return back()->withErrors(['Vous n\'avez pas accès à cette branche.']);
        }

        // Récupérer les informations de la branche
        $branch = DB::table('branches')->where('id', $request->branch_id)->first();

        // Enregistrer en session
        Session::put('selected_branch_id', $request->branch_id);
        Session::put('selected_branch_name', $branch->name);

        // Rediriger vers le dashboard
        return redirect('/home');
    }
}
