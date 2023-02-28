<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Operation;
use App\Models\Ressource;
use App\Models\Permission;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }
    
    protected function store_roles_permission($role, $value, $operation)
    {   
        $ope->id = Operation::whereOperation($operation)->first();
        $permission = Permission::whereRessourceId($value)->whereOperationId($ope->id)->get();
        $role->permissions()->save($permission);
    }

    public function index()
    {
        if (!getOnlineUser()->can('view-roles')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $roles = Role::latest()->get();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('users.roles.index', compact('roles'));

    }

    public function create()
    {
        if (!getOnlineUser()->can('create-roles')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $permissions = Permission::all();
        $ressources = Ressource::all();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('users.roles.create', compact('permissions', 'ressources'));
        
    }

    public function store(Request $request)
    {
        if (!getOnlineUser()->can('create-roles')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $view = [];
        $create = [];
        $edit = [];
        $delete = [];
        $view = $request->view;
        $create = $request->create;
        $edit = $request->edit;
        $delete = $request->delete;
        // dd($request);

        $role = Role::updateOrCreate(
            ['name' => $request->titre, 'slug' => Str::slug($request->titre)],
            ['created_by' => Auth::user()->id, 'description' => $request->description]
        );

            if ($request->create) {
                $operation = "create";
                foreach ($create as $key => $value) {

                    $ressource = Ressource::findorfail($value); 
                    $operation = Operation::whereOperation("create")->first();
                    $permission = Permission::whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);

                }
            }
            
            if ($request->view) {
                $operation = "view";
                foreach ($view as $key => $value) {
                    
                    $ressource = Ressource::findorfail($value); 
                    $operation = Operation::whereOperation("view")->first();
                    $permission = Permission::whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);

                }
            }

            if ($request->edit) {
                $operation = "edit";
                foreach ($edit as $key => $value) {
                    $ressource = Ressource::findorfail($value); 
                    $operation = Operation::whereOperation("edit")->first();
                    $permission = Permission::whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);
            }
            }
            
            if ($request->delete) {
                $operation = "delete";
                foreach ($delete as $key => $value) {
                    $ressource = Ressource::findorfail($value); 
                    $operation = Operation::whereOperation("delete")->first();
                    $permission = Permission::whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);
                }
    
            }

        return redirect()->route('user.role-index')->with('success', " Role crée ! ");

    }

    public function show($slug)
    {
        $role = Role::whereSlug($slug)->first();
        $permissions = Permission::all();
        $ressources = Ressource::all();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('users.roles.show', compact('role', 'permissions', 'ressources'));
        
    }

    public function update(Request $request)
    {
        if (!getOnlineUser()->can('edit-roles')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $view = [];
        $create = [];
        $edit = [];
        $delete = [];
        $view = $request->view;
        $create = $request->create;
        $edit = $request->edit;
        $delete = $request->delete;

        try {

            $role = Role::updateOrCreate(
                ["id" => $request->id],
                ['name' => $request->titre, 'slug' => Str::slug($request->titre), 'created_by' => Auth::user()->id, 'description' => $request->description]
            );
    
            $arrPermissionView = [];
            $arrPermissionDelete = [];
            $arrPermissionEdit = [];
            $arrPermissionCreate = [];
            $role->permissions()->sync([]);
    
            if ($request->create) {
                $operation = "create";
    
                foreach ($create as $key => $value) {
                    $ressource = Ressource::findorfail($value); 
                    $operation = Operation::whereOperation("create")->first();
                    $permission = Permission::whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);
                }
            }        
            
            if ($request->view) {
                $operation = "view";
    
                foreach ($view as $key => $value) {
                    $ressource = Ressource::findorfail($value); 
                    $operation = Operation::whereOperation("view")->first();
                    $permission = Permission::whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);
                }
            }
            if ($request->edit) {
                $operation = "edit";
    
                foreach ($edit as $key => $value) {
                    $ressource = Ressource::findorfail($value); 
                    $operation = Operation::whereOperation("edit")->first();
                    $permission = Permission::whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);
                }
            }        
            if ($request->delete) {
                $operation = "delete";
    
                foreach ($delete as $key => $value) {
                    $ressource = Ressource::findorfail($value); 
                    $operation = Operation::whereOperation("delete")->first();
                    $permission = Permission::whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);
                }
            }
    
            return redirect()->route('user.role-index')->with('success', "Role mis à jours ! ");
        } catch (\Throwable $th) {
            return redirect()->route('user.role-index')->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());

        }
    }

    public function destroy($id)
    {
        if (!getOnlineUser()->can('delete-demandes-examens')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        Role::find($id)->delete();
        return redirect()->route('user.role-index')->with('success', "    Un élement a été supprimé ! ");
    }
}
