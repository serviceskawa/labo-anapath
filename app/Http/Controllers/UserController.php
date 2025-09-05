<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class UserController extends Controller
{
    protected $role;
    protected $setting;
    protected $user;
    public function __construct(Role $role, Setting $setting, User $user)
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
        // if (!getOnlineUser()->can('view-users')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $users = $this->user->latest()->get();
        $roles = $this->role->all();
        $user = Auth::user();
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if (!getOnlineUser()->can('create-users')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $users = $this->user->latest()->get();
        $roles = $this->role->all();
        $branches = Branch::latest()->get();
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('users.create', compact('users', 'roles', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!getOnlineUser()->can('create-users')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        if ($request->hasFile('signature')) {
            $signature = time() . '_' . $request->firstname . '_signature.' . $request->file('signature')->extension();
            // Chemin absolu vers public/adminassets/images
            $destinationPath = public_path('adminassets/images');
            // Déplacer le fichier vers le dossier public
            $request->file('signature')->move($destinationPath, $signature);
        }

        try {
            $user = $this->user->firstOrCreate(["email" => $request->email], [
                "firstname" => $request->firstname,
                "lastname" => $request->lastname,
                "commission" => $request->commission,
                "whatsapp" => $request->whatsapp,
                "telephone" => $request->telephone,
                "password" => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                "signature" => $request->file('signature') ? $signature : '',
                // "signature" => $request->file('signature') ? $signature : '',
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

            // Enregistrement des branches
            foreach ($request->branches as $branchId) {
                DB::table('branch_user')->insert([
                    'user_id'    => $user->id,             // L'ID de l'utilisateur
                    'branch_id'  => intval($branchId),     // L'ID de la branche à associer
                    'is_default' => true,                         // Marque comme branche par défaut
                    'created_at' => Carbon::now(),                // Timestamp de création
                    'updated_at' => Carbon::now(),                // Timestamp de mise à jour
                ]);
            }

            return redirect()->route('user.index')->with('success', " Utilisateur crée ! ");
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('error', "Échec de l'enregistrement ! " . $th->getMessage());
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
        // if (!getOnlineUser()->can('edit-users')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $user = $this->user->findorfail($id);
        $roles = $this->role->all();
        $branches = Branch::latest()->get();
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('users.edit', compact('user', 'roles', 'branches'));
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
        // if (!getOnlineUser()->can('edit-users')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $data = $this->validate($request, [
            'id' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
        ]);

        // Vérifiez si un fichier image a été envoyé via la requête
        $namefichier = "";
        if ($request->hasFile('signature')) {
            $imageFile = $request->file('signature');
            // Obtenez le nom d'origine du fichier
            $namefichier = time()."_".Auth::user()->firstname . "_" . Auth::user()->lastname . "." . $imageFile->getClientOriginalExtension();

            // Enregistrez le fichier image dans le dossier public
            $request->file('signature')->move(public_path('adminassets/images'), $namefichier);
        } else {
            $user = $this->user->find($data['id']);
            $namefichier = $user->signature;
        }

        try {
            $user = $this->user->find($data['id']);
            $user = $this->user->updateorcreate(["id" => $request->id], [
                "email" => $request->email,
                "firstname" => $request->firstname,
                "lastname" => $request->lastname,
                "commission" => $request->commission,
                "whatsapp" => $request->whatsapp,
                "telephone" => $request->telephone,
                "signature" => $namefichier,
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

            // Suppression des branches associées à l'utilisateur
            if ($request->branches) {
                DB::table('branch_user')->where('user_id', $user->id)->delete();
                // Enregistrement des branches
                foreach ($request->branches as $branchId) {
                    DB::table('branch_user')->insert([
                        'user_id'    => $user->id,             // L'ID de l'utilisateur
                        'branch_id'  => intval($branchId),     // L'ID de la branche à associer
                        'is_default' => true,                         // Marque comme branche par défaut
                        'created_at' => Carbon::now(),                // Timestamp de création
                        'updated_at' => Carbon::now(),                // Timestamp de mise à jour
                    ]);
                }
            }

            return redirect()->route('user.index')->with('success', " Utilisateur mis à jour ! ");
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('error', "Échec de l'enregistrement ! " . $th->getMessage());
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

        $status = "";
        try {

            if ($user->is_active == 1) {

                $user->is_active = 0;
                $user->is_connect = 0;
                $user->two_factor_enabled = 0;
                $user->two_factor_enabled = 0;
                $user->save();
                $status = "désactivé";
                // Déconnectez l'utilisateur s'il est actuellement connecté
                // if (Auth::check() && Auth::id() === $user->id) {
                //     Auth::logout();
                // }

            } else {
                $user->is_active = 1;
                $user->save();
                $status = "activé";
            }
            return back()->with('success', 'Le compte a été ' . $status);
        } catch (\Throwable $th) {
            back()->with('error', 'Une erreur est subvenue' . $th);
        }
    }

    public function checkrole($id)
    {
        $user = User::find(Auth::user()->id);
        return response()->json($user->userCheckRole($id));
    }
}
