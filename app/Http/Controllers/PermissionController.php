<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Models\Operation;
use App\Models\Ressource;
use App\Models\Permission;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissions;
    protected $ressources;
    protected $operations;
    protected $setting;

    public function __construct(Permission $permissions, Ressource $ressources, Operation $operations, Setting $setting)
    {
        $this->middleware('auth'); 
        $this->permissions = $permissions;
        $this->ressources = $ressources;
        $this->operations = $operations;
        $this->setting = $setting;
    }
    
    public function create()
    {
        if (!getOnlineUser()->can('view-permissions')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $permissions = $this->permissions->all();
            $ressources = $this->ressources->all();
            $operations = $this->operations->all();
            $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
            return view('users.permissions.create', compact('permissions', 'ressources', 'operations'));

    }

    public function store(PermissionRequest $request)
    {
        if (!getOnlineUser()->can('create-permissions')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $op = $this->operations->findorfail($request->operation);
        $ress = $this->ressources->findorfail($request->ressource);

        $permissionData = [
            'titre' => $request->titre,
            'slug' => Str::slug($op->operation.' '.$ress->slug),
            'operation_id' => $op->id,
            'ressource_id' => $ress->id,
        ];
       
        try {
            $this->permissions->create($permissionData);
            return back()->with('success', "Une permission a été enregistrée ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }
}
