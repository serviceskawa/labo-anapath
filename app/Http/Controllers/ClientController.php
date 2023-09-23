<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\Setting;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $client;
    protected $setting;
    public function __construct( Client $client, Setting $setting)
    {
        $this->middleware('auth');
        $this->client = $client;
        $this->setting = $setting;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-clients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        //récupérer les données de la table doctor dans l'ordre croissant des noms
        $clients = $this->client->latest()->get();

        $setting = $this->setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('clients.index',compact(['clients']));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!getOnlineUser()->can('create-clients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }


        // Récupérer les données saisir par l'utilisateur et qui respectent les conditions
        $clientData = [
            'name' => $request->name,
            'contact' => $request->contact,
            'adress' => $request->adress,
            'ifu' => $request->ifu
        ];


        // insérer les données dans la base de données
        try {
            Client::create($clientData);
            return back()->with('success', "Un client enregistré ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if (!getOnlineUser()->can('edit-clients')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $doctorData = $this->client->find($id);
        return response()->json($doctorData);
    }

    public function update(Request $request)
    {
        if (!getOnlineUser()->can('edit-clients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $clientData = [
            'id' => $request->id,
            'name' => $request->name,
            'contact' => $request->contact,
            'adress' => $request->adress,
            'ifu' => $request->ifu
        ];


        try {

            $doctor = Client::find($clientData['id']);
            $doctor->name = $clientData['name'];
            $doctor->contact = $clientData['contact'];
            $doctor->ifu = $clientData['ifu'];
            $doctor->adress = $clientData['adress'];
            $doctor->save();

            return back()->with('success', "Un client a été mis à jour ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
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
        if (!getOnlineUser()->can('delete-clients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $this->client->find($id)->delete();
        return back()->with('success', "    Un élement a été supprimé ! ");
    }
}
