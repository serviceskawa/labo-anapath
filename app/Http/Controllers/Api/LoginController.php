<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $user = User::with('roles')->where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status_code'=>401,
                'status_message'=> 'Aucune correspondance pour ce mail'
            ],401);
        }

        if(!Hash::check($request->password,$user->password)) {
            return response()->json([
                'status_code'=>401,
                'status_message'=> 'Mot de passe incorrecte'
            ],401);
        }

        return response()->json([
            'status_code'=>200,
            'status_message'=> 'Vous êtes connecté',
            'user' => $user,
            'role' =>$user['roles']
        ]);
    }
}
