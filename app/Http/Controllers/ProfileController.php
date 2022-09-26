<?php

namespace App\Http\Controllers;

use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        $this->validate($request, [ 
            'oldpassword' => 'required',
            'newpassword' => 'required',
        ]);
 
        $hashedPassword = Auth::user()->password;

        if (Hash::check($request->oldpassword , $hashedPassword)) {
            dd($request->oldpassword , $hashedPassword);

            if (Hash::check($request->newpassword , $hashedPassword)) {
 
                $users = User::find(Auth::user()->id);
                $users->password = bcrypt($request->newpassword);
                $users->save();

                return back()->with('success', " Le nouveau mot de passe enregistré! ");

            }
            else{
                return back()->with('error', " Le nouveau mot de passe ne peut pas être l'ancien mot de passe! ");

            } 
        }
        else{
            return back()->with('error', " Le mot de passe ne correspond pas ");

        }
    }
}
