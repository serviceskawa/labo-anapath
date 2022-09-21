<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\Ressource;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function create()
    {
        $permissions = Permission::all();
        $ressources = Ressource::all();
        $operations = Operation::all();
        return view('users.permissions.create', compact('permissions', 'ressources', 'operations'));
    }

    public function store(Request $request)
    {
        // dd($request);
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
