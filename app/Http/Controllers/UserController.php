<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-users')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $users = User::all();
        $roles = Role::all();

        $user = Auth::user();
        // dd($user->hasRole('test-contrats'), $user->can('delete.hopitaux'));
        
        return view('users.index', compact('users','roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!getOnlineUser()->can('create-users')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $users = User::all();
        $roles = Role::all();
        return view('users.create', compact('users','roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!getOnlineUser()->can('create-users')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        // dd($request);
        $data = $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users,email',
        ]);

       

        try {
            $user = User::firstOrCreate(["email" =>$request->email],[
                "firstname" => $request->firstname,
                "lastname" => $request->lastname,
                "password" => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ]);
            $user->roles()->attach($request->roles);
            
            $permsTab = [];
            foreach ($request->roles as $key => $role_id) {
                $role = Role::findorfail($role_id);
                foreach ($role->permissions as $key => $perms) {
                    $permsTab[] = $perms->id;
                }

            }
            $user->permissions()->attach($permsTab);
    
            return redirect()->route('user.index')->with('success', " Utilisateur crée ! ");
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());

        }

        return redirect()->back()->with('success', "   Examen finalisé ! ");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!getOnlineUser()->can('edit-users')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $user = User::findorfail($id);
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!getOnlineUser()->can('edit-users')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
        ]);

        // dd($request->roles);

        try {
            $user = User::updateorcreate(["id" =>$request->id],[
                "email" =>$request->email,
                "firstname" => $request->firstname,
                "lastname" => $request->lastname,
            ]);
            $user->roles()->sync([]);
            $user->roles()->attach($request->roles);

            $permsTab = [];
            foreach ($request->roles as $key => $role_id) {
                $role = Role::findorfail($role_id);

                foreach ($role->permissions as $key => $perms) {
                    $permsTab[] = $perms->id;
                }

            }
            $user->permissions()->sync([]);

            $user->permissions()->attach($permsTab);

            return redirect()->route('user.index')->with('success', " Utilisateur mis à jour ! ");
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('error', "Échec de l'enregistrement ! " .$th->getMessage());

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
