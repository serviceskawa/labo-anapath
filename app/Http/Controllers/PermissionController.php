<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\Ressource;
use App\Models\Permission;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }
    
    public function create()
    {
        if (!getOnlineUser()->can('view-permissions')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $permissions = Permission::all();
            $ressources = Ressource::all();
            $operations = Operation::all();
            $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
            return view('users.permissions.create', compact('permissions', 'ressources', 'operations'));

    }

    public function store(Request $request)
    {
        if (!getOnlineUser()->can('create-permissions')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $this->validate($request, [
            'titre' => 'required',
        ]);

        $op = Operation::findorfail($request->operation);
        $ress = Ressource::findorfail($request->ressource);
        try {
            Permission::create([
                "titre" => $request->titre, 
                "slug" => Str::slug($op->operation.' '.$ress->slug),
                "operation_id" => $op->id,
                "ressource_id" => $ress->id,
            ]);
            return back()->with('success', "Une permission a été enregistrée ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }
}
