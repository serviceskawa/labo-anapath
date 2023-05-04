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
        $operation->id = (new Operation)->whereOperation($operation)->first();
        $permission = (new Permission)->whereRessourceId($value)->whereOperationId($operation->id)->get();
        $role->permissions()->save($permission);
    }

    public function index()
    {
        if (!getOnlineUser()->can('view-roles')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $roles = (new Role)->latest()->get();
        $setting = (new Setting)->find(1);
        config(['app.name' => $setting->titre]);
        return view('users.roles.index', compact('roles'));

    }

    public function create()
    {
        if (!getOnlineUser()->can('create-roles')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $permissions = (new Permission)->all();
        $ressources = (new Ressource)->all();
        $setting = (new Setting)->find(1);
        config(['app.name' => $setting->titre]);
        return view('users.roles.create', compact('permissions', 'ressources'));
        
    }

    public function store(Request $request)
    {
        if (!getOnlineUser()->can('create-roles')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        
        $view = $request->input('view', []);
        $create = $request->input('create', []);
        $edit = $request->input('edit', []);
        $delete = $request->input('delete', []);

        $role = (new Role)->updateOrCreate(
            ['name' => $request->titre, 'slug' => Str::slug($request->titre)],
            ['created_by' => Auth::user()->id, 'description' => $request->description]
        );

            if ($request->create) {
                $operation = "create";
                foreach ($create as $key => $value) {

                    $ressource = (new Ressource)->findorfail($value); 
                    $operation = (new Operation)->whereOperation("create")->first();
                    $permission = (new Permission)->whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);

                }
            }
            
            if ($request->view) {
                $operation = "view";
                foreach ($view as $key => $value) {
                    
                    $ressource = (new Ressource)->findorfail($value); 
                    $operation = (new Operation)->whereOperation("view")->first();
                    $permission = (new Permission)->whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);

                }
            }

            if ($request->edit) {
                $operation = "edit";
                foreach ($edit as $key => $value) {
                    $ressource = (new Ressource)->findorfail($value); 
                    $operation = (new Operation)->whereOperation("edit")->first();
                    $permission = (new Permission)->whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);
            }
            }
            
            if ($request->delete) {
                $operation = "delete";
                foreach ($delete as $key => $value) {
                    $ressource = (new Ressource)->findorfail($value); 
                    $operation = (new Operation)->whereOperation("delete")->first();
                    $permission = (new Permission)->whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);
                }
    
            }

        return redirect()->route('user.role-index')->with('success', " Role crée ! ");

    }

    public function show($slug)
    {
        $role = (new Role)->whereSlug($slug)->first();
        $permissions = (new Permission)->all();
        $ressources = (new Ressource)->all();
        $setting = (new Setting)->find(1);
        config(['app.name' => $setting->titre]);
        return view('users.roles.show', compact('role', 'permissions', 'ressources'));
        
    }

    public function update(Request $request)
    {
        if (!getOnlineUser()->can('edit-roles')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $view = $request->input('view', []);
        $create = $request->input('create', []);
        $edit = $request->input('edit', []);
        $delete = $request->input('delete', []);

        try {

            $role = (new Role)->updateOrCreate(
                ["id" => $request->id],
                ['name' => $request->titre, 'slug' => Str::slug($request->titre), 'created_by' => Auth::user()->id, 'description' => $request->description]
            );
    
           
            $role->permissions()->sync([]);
    
            if ($request->create) {
                $operation = "create";
    
                foreach ($create as $key => $value) {
                    $ressource = (new Ressource)->findorfail($value); 
                    $operation = (new Operation)->whereOperation("create")->first();
                    $permission = (new Permission)->whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);
                }
            }        
            
            if ($request->view) {
                $operation = "view";
    
                foreach ($view as $key => $value) {
                    $ressource = (new Ressource)->findorfail($value); 
                    $operation = (new Operation)->whereOperation("view")->first();
                    $permission = (new Permission)->whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);
                }
            }
            if ($request->edit) {
                $operation = "edit";
    
                foreach ($edit as $key => $value) {
                    $ressource = (new Ressource)->findorfail($value); 
                    $operation = (new Operation)->whereOperation("edit")->first();
                    $permission = (new Permission)->whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
                    $role->permissions()->attach($permission);
                }
            }        
            if ($request->delete) {
                $operation = "delete";
    
                foreach ($delete as $key => $value) {
                    $ressource = (new Ressource)->findorfail($value); 
                    $operation = (new Operation)->whereOperation("delete")->first();
                    $permission = (new Permission)->whereOperationId($operation->id)->whereRessourceId($ressource->id)->first();
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
        if (!getOnlineUser()->can('delete-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        (new Role)->find($id)->delete();
        return redirect()->route('user.role-index')->with('success', "    Un élement a été supprimé ! ");
    }
}
