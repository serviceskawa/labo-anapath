<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    protected function store_roles_permission($role, $value, $operation)
    {
        $permission = Permission::find($value);
        $role->permissions()->save($permission, ['operation' => $operation]);
    }

    public function index()
    {
        $roles = Role::all();
        return view('users.roles.index', compact('roles'));
    }

    public function create()
    {
        // dd(Auth::user()->can('create.contr'));
        $permissions = Permission::all();
        return view('users.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {

        $view = [];
        $create = [];
        $edit = [];
        $delete = [];
        $view = $request->view;
        $create = $request->create;
        $edit = $request->edit;
        $delete = $request->delete;
        // dd($request);

        try {
            $role = Role::updateOrCreate(
                ['name' => $request->titre, 'slug' => Str::slug($request->titre)],
                ['created_by' => Auth::user()->id, 'description' => $request->description]
            );
    
                if ($request->create) {
                    $operation = "create";
                    foreach ($create as $key => $value) {
                        
                        if (!getPermission($role->id, $operation, $value)) {
                            $this->store_roles_permission($role, $value, $operation);
        
                        }
    
                    }
                }
                
                if ($request->view) {
                    $operation = "view";
                    foreach ($view as $key => $value) {
                        
                        if (!getPermission($role->id, $operation, $value)) {
                            $this->store_roles_permission($role, $value, $operation);
        
                        }
    
                    }
                }
    
                if ($request->edit) {
                    $operation = "edit";
                    foreach ($edit as $key => $value) {
                    if (!getPermission($role->id, $operation, $value)) {
                        $this->store_roles_permission($role, $value, $operation);
    
                    }
                }
                }
                
                if ($request->delete) {
                    $operation = "delete";
                    foreach ($delete as $key => $value) {
                        if (!getPermission($role->id, $operation, $value)) {
                            $this->store_roles_permission($role, $value, $operation);
        
                        }
                    }
        
                }
    
            return redirect()->route('user.role-index')->with('success', " Role crée ! ");
        } catch (\Throwable $th) {
            return redirect()->route('user.role-index')->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }

    }

    public function show($slug)
    {
        $role = Role::whereSlug($slug)->first();
        $permissions = Permission::all();
        return view('users.roles.show', compact('role', 'permissions'));
    }

    public function update(Request $request)
    {
        // dd($request);

        $view = [];
        $create = [];
        $edit = [];
        $delete = [];
        $view = $request->view;
        $create = $request->create;
        $edit = $request->edit;
        $delete = $request->delete;

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
                $permission = Permission::find($value);
                $arrPermissionCreate[$permission->id] = ['operation' => $operation ];
            }
        }        
        
        if ($request->view) {
            $operation = "view";

            foreach ($view as $key => $value) {
                $permission = Permission::find($value);
                $arrPermissionView[$permission->id] = ['operation' => $operation ];
            }
        }
        if ($request->edit) {
            $operation = "edit";

            foreach ($edit as $key => $value) {
                $permission = Permission::find($value);
                $arrPermissionEdit[$permission->id] = ['operation' => $operation ];
            }
        }        
        if ($request->delete) {
            $operation = "delete";

            foreach ($delete as $key => $value) {
                $permission = Permission::find($value);
                $arrPermissionDelete[$permission->id] = ['operation' => $operation ];
            }
        }

        try {
            $role->permissions()->attach($arrPermissionView);    
            $role->permissions()->attach($arrPermissionCreate);    
            $role->permissions()->attach($arrPermissionEdit); 
            $role->permissions()->attach($arrPermissionDelete); 
    
            return redirect()->route('user.role-index')->with('success', "Role mis à jours ! ");
        } catch (\Throwable $th) {
            return redirect()->route('user.role-index')->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());

        }
       
    }
}
