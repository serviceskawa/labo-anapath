<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;

class UserController extends Controller
{
    protected $role;
    protected $setting;
    protected $user;
    public function __construct(Role $role, Setting $setting, User $user )
    {
        $this->middleware('auth');
        $this->role = $role;
        $this->setting = $setting;
        $this->user = $user;
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
        $users = $this->user->latest()->get();
        $roles = $this->role->all();

        $user = Auth::user();

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
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
        $users = $this->user->latest()->get();
        $roles = $this->role->all();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
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

        if ($request->file('signature') ) {
            $signature = time() . '_'. $request->firstname .'_signature.' . $request->file('signature')->extension();

            $path_signature = $request->file('signature')->storeAs('settings/app', $signature, 'public');
        }

        try {
           // dd($path_signature);
            $user = $this->user->firstOrCreate(["email" =>$request->email],[
                "firstname" => $request->firstname,
                "lastname" => $request->lastname,
                "password" => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                "signature" => $request->file('signature') ? $path_signature:'',
            ]);
            $user->roles()->attach($request->roles);

            $permsTab = [];
            foreach ($request->roles as $key => $role_id) {
                $role = $this->role->findorfail($role_id);
                foreach ($role->permissions as $key => $perms) {
                    $permsTab[] = $perms->id;
                }

            }
            $user->permissions()->attach($permsTab);

            return redirect()->route('user.index')->with('success', " Utilisateur crée ! ");
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('error', "Échec de l'enregistrement ! " .$th->getMessage());

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

        $user = $this->user->findorfail($id);
        $roles = $this->role->all();

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
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
            'id' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
        ]);

        // if ($request->file('signature') ) {
        //     $signature = time() . '_'. $request->firstname .'_signature.' . $request->file('signature')->extension();
        //     $path_signature = $request->file('signature')->storeAs('settings/app', $signature, 'public');
        // }

        // $imageFile = $request->file('signature');

        // Déterminez le nom de fichier (avec son extension)
        // $imageName = $imageFile->getClientOriginalName();

             // Récupérez uniquement l'extension du fichier
            //  $name = Auth::user()->firstname."_".Auth::user()->lastname.".".$imageFile->getClientOriginalExtension();

        // dd($name);

        // Vérifiez si un fichier image a été envoyé via la requête
    if ($request->hasFile('signature')) {
        $imageFile = $request->file('signature');
        // Obtenez le nom d'origine du fichier
        $namefichier = Auth::user()->firstname."_".Auth::user()->lastname.".".$imageFile->getClientOriginalExtension();

        // Enregistrez le fichier image dans le dossier public
        $re = $request->file('signature')->move(public_path('adminassets/images'), $namefichier);
    }else{
        $user= $this->user->find($data['id']);
        $namefichier = $user->signature;
    }

    // dd($re);

        try {


            $user = $this->user->find($data['id']);


            $user = $this->user->updateorcreate(["id" =>$request->id],[
                "email" =>$request->email,
                "firstname" => $request->firstname,
                "lastname" => $request->lastname,
                "signature" => $namefichier ? $namefichier : '',
            ]);
            
            $user->roles()->sync([]);
            $user->roles()->attach($request->roles);

            $permsTab = [];
            foreach ($request->roles as $key => $role_id) {
                $role = $this->role->findorfail($role_id);

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
        if (!getOnlineUser()->can('delete-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $this->user->find($id)->delete();
        return redirect()->route('user.index')->with('success', "Un utilisateur a été supprimé ! ");
    }

    public function updateActiveStatus($id)
    {
        $user = $this->user->find($id);

        $status ="";
       try
       {

            if($user->is_active ==1){

                $user->is_active = 0;
                $user->is_connect = 0;
            $user->two_factor_enabled =0;
                $user->two_factor_enabled = 0;
                $user->save();
                $status = "désactivé";
                // Déconnectez l'utilisateur s'il est actuellement connecté
                // if (Auth::check() && Auth::id() === $user->id) {
                //     Auth::logout();
                // }

            }else{
                $user->is_active = 1;
                $user->save();
                $status = "activé";
            }
            // dd($user);
            return back()->with('success','Le compte a été '.$status);
       }catch (\Throwable $th) {
        back()->with('error','Une erreur est subvenue'.$th);
       }
    }

    public function checkrole($id)
    {
        $user = User::find(Auth::user()->id);
        return response()->json($user->userCheckRole($id));
    }
}
