<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function create()
    {
        $permissions = Permission::all();
        
        return view('users.permissions.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'titre' => 'required',
        ]);

        try {
            Permission::create([
                "titre" => $request->titre, 
                "slug" => Str::slug($request->titre)
            ]);
            return back()->with('success', "Une permission a été enregistrée ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }
}
