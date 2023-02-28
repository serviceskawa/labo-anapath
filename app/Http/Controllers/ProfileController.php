<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('profile.index');
    }

    public function updateName(Request $request)
    {
        $this->validate($request, [ 
            'firstname' => 'required',
            'lastname' => 'required',
        ]);

        if ($request->file('signature') ) {
            $signature = time() . '_'. $request->firstname .'_signature.' . $request->file('signature')->extension();  
            
            $path_signature = $request->file('signature')->storeAs('settings/app', $signature, 'public');
        }

        $user = Auth::user(); 

        if ($user) {
            $user = User::find(Auth::user()->id);
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->signature = $request->file('signature') ? $path_signature:'';
            $user->save();

            return back()->with('success', " Informations mis à jour ");
        }

        return back()->with('error', " Veuillez reessayer ");

    }

    public function update(Request $request)
    {
        $this->validate($request, [ 
            'oldpassword' => 'required',
            'newpassword' => 'required',
        ]);
 
        $hashedPassword = Auth::user()->password;

        if (Hash::check($request->oldpassword , $hashedPassword)) {
            // dd($request->oldpassword , $hashedPassword);

            $users = User::find(Auth::user()->id);
            $users->password = bcrypt($request->newpassword);
            $users->save();

            return back()->with('success', " Le nouveau mot de passe enregistré! ");

        }
        else{
            return back()->with('error', " Le mot de passe ne correspond pas ");

        }
    }
}
