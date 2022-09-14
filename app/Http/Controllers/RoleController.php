<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('users.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        dd($request);

        $read = [];
        $edit = [];
        $refused = [];
        $read = $request->read;
        $edit = $request->edit;
        $refused = $request->refused;
        // dd($request);
        $role = Role::updateOrCreate(
            ['name' => $request->modalRoleName, 'slug' => Str::slug($request->modalRoleName)],
            ['created_by' => Auth::user()->id, 'description' => $request->description, 'type' => "admin"]
        );

            if ($request->read) {
                $operation = "read";
                foreach ($read as $key => $value) {
                    
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
            
            if ($request->refused) {
                $operation = "refused";
                foreach ($refused as $key => $value) {
                    if (!getPermission($role->id, $operation, $value)) {
                        $this->store_roles_permission($role, $value, $operation);
    
                    }
                }
    
            }
    }
}
