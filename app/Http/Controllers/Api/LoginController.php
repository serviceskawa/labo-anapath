<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use Carbon\Carbon;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // public function login(Request $request)
    // {
    //     $user = User::with('roles')->where('email', $request->email)->first();

    //     if (!$user) {
    //         return response()->json([
    //             'status_code'=>401,
    //             'status_message'=> 'Aucune correspondance pour ce mail'
    //         ],401);
    //     }

    //     if(!Hash::check($request->password,$user->password)) {
    //         return response()->json([
    //             'status_code'=>401,
    //             'status_message'=> 'Mot de passe incorrecte'
    //         ],401);
    //     }

    //     return response()->json([
    //         'status_code'=>200,
    //         'status_message'=> 'Vous êtes connecté',
    //         'user' => $user,
    //         'role' =>$user['roles']
    //     ]);
    // }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
                return $this->respondFailedLogin(false, true);
        } else {
            $user = auth()->user();

            $tokenResult = $user->createToken('auth_token');

            return $this->respondWithToken($tokenResult, $user);
        }
        
    }

    protected function respondFailedLogin() {
        return $this->respond([
            'errors' => [
                'message' => 'email or password is invalid'
            ]
        ], 422);
    }

    protected function respondWithToken($tokenResult, $user)
    {
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => new UserResource($user)
        ], 200);
    }
}
