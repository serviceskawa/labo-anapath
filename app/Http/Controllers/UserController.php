<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $roles = Role::all();

        return view('users.index', compact('users','roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
                "password" => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            ]);

            $user->roles()->attach($request->roles);
    
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
        $data = $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
        ]);


        try {
            $user = User::updateorcreate(["id" =>$request->id],[
                "email" =>$request->email,
                "firstname" => $request->firstname,
                "lastname" => $request->lastname,
            ]);

            // dd($user);
            $user->roles()->attach($request->roles);
    
            return redirect()->route('user.index')->with('success', " Utilisateur crée ! ");
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());

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
