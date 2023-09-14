<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContratDetailRequest;
use App\Http\Requests\ContratRequest;
use App\Models\CategoryTest;
use App\Models\Client;
use App\Models\Contrat;
use App\Models\Details_Contrat;
use App\Models\Invoice;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContratController extends Controller
{


    protected $contrat;
    protected $setting;
    protected $invoices;
    protected $categoryTest;
    protected $detailsContrat;
    protected $clients;


    public function __construct(Contrat $contrat, Setting $setting, CategoryTest $categoryTest, Details_Contrat $detailsContrat, Client $clients, Invoice $invoice)
    {

        $this->contrat = $contrat;
        $this->clients = $clients;
        $this->setting = $setting;
        $this->invoices = $invoice;
        $this->categoryTest = $categoryTest;
        $this->detailsContrat = $detailsContrat;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        //récupération des contrats avec les détails
        // $contrats = $this->contrat->getWithDetail();
        $contrats = $this->contrat->latest()->get();
        $clients = $this->clients->latest()->get();

        $setting = $this->setting->find(1);

        config(['app.name' => $setting->titre]);
        return view('contrats.index', compact(['contrats','clients']));
    }

    public function details_index($id)
    {

        //si l'utilisateur connecté n'a pas les permissions nécessaires
        if (!getOnlineUser()->can('view-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        //récupérer le contrat d'in $id
        $contrat = $this->contrat->find($id);

        //récupérer toutes les categories d'examen
        $cateroriesTests = $this->categoryTest->all();

        //récupérer les détails d'un contrat
        $details = $this->detailsContrat->where('contrat_id', $contrat->id)->get();
        // dd($details);

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('contrats_details.index', compact(['contrat', 'details', 'cateroriesTests']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContratRequest $request)
    {
        if (!getOnlineUser()->can('create-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }


        $data =[
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'nbr_tests' => $request->nbr_examen,
            'invoice_unique' => $request->invoice_unique?0:1,
            'client_id' => $request->client_id,
            'status' => 'INACTIF',
        ];

        try {
            $contrat = $this->contrat->create($data);
            if ($contrat->invoice_unique==0) {
                $code_facture = generateCodeFacture();
                 $invoice = $this->invoices->create([
                    "date" => Carbon::now(),
                    "contrat_id" => $contrat->id,
                    "client_name" => $contrat->client->name,
                    "code" => $code_facture
                ]);
            }

            return redirect()->route('contrat_details.index', $contrat->id)->with('success', "Contrat enregistré avec succès ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    public function details_store(ContratDetailRequest $request)
    {

        if (!getOnlineUser()->can('create-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = [
            'contrat_id' => $request->contrat_id,
            'pourcentage' => $request->pourcentage,
            'category_test_id' => $request->category_test_id,
        ];

        $contrat = Contrat::findorfail($data['contrat_id']);
        $category_exit = $contrat->detail()->whereCategoryTestId($data['category_test_id'])->exists();

        if ($category_exit) {
            return back()->with('error', "Cette categorie existe ! ");
        }

        try {

            DB::transaction(function () use ($data) {
                Details_Contrat::create($data);
            });

            return back()->with('success', "Element enregistré avec succès ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contrat  $contrat
     * @return \Illuminate\Http\Response
     */
    public function show(Contrat $contrat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contrat  $contrat
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!getOnlineUser()->can('edit-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->contrat->find($id);
        return response()->json($data);
    }

    public function contrat_details_edit($id)
    {
        if (!getOnlineUser()->can('edit-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->detailsContrat->find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contrat  $contrat
     * @return \Illuminate\Http\Response
     */
    public function update(ContratRequest $request)
    {
        if (!getOnlineUser()->can('edit-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = [
            'name' => $request->name,
            'type' => $request->type,
            'status' => $request->statis,
            'id' => $request->id,
            'description' => $request->description,
            'nbr_examen' => $request->nbr_examen,
        ];

        try {
            DB::transaction(function () use ($data) {

                $contrat = $this->contrat->find($data['id']);
                $contrat->name = $data['name'];
                $contrat->type = $data['type'];
                $contrat->status = $data['status'];
                $contrat->nbr_tests = $data['nbr_examen'];
                $contrat->description = $data['description'];
                $contrat->save();
            });

            return back()->with('success', "Mise à jour effectuée avec succès ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    public function contrat_details_update(ContratDetailRequest $request)
    {

        if (!getOnlineUser()->can('edit-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = [
            'category_test_id' => $request->category_test_id,
            'pourcentage' => $request->pourcentage,
            'contrat_id' => $request->contrat_id,
            'contrat_details_id' => $request->contrat_details_id,
        ];

        try {
            DB::transaction(function () use ($data) {
                $contrat_detail = $this->detailsContrat->find($data['contrat_details_id']);
                $contrat_detail->category_test_id = $data['category_test_id'];
                $contrat_detail->pourcentage = $data['pourcentage'];
                $contrat_detail->save();
            });

            return back()->with('success', "Mise à jour effectué avec succès ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contrat  $contrat
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!getOnlineUser()->can('delete-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        // dd($id);
        $contrat = $this->contrat->find($id)->delete();
        if ($contrat) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Ce contrat est utilisé ailleurs ! ");
        }
    }

    public function close($id)
    {
        if (!getOnlineUser()->can('delete-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        // dd($id);
        $contrat = $this->contrat->findorfail($id);

        if ($contrat) {
            $contrat->is_close = 1;
            $contrat->save();
            return back()->with('success', "    Un élement a été clôturé ! ");
        } else {
            return back()->with('error', "    Ce contrat est utilisé ailleurs ! ");
        }
    }

    public function destroy_details($id)
    {
        if (!getOnlineUser()->can('delete-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        //Suppression d'un contrat
        $detail = $this->detailsContrat->find($id)->delete();

        if ($detail) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Ce contrat est utilisé ailleurs ! ");
        }
        // return back()->with('success', "    Un élement a été supprimé ! ");
    }

    public function update_detail_status($id)
    {
        if (!getOnlineUser()->can('edit-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        //Mis à jour d status d'un contrat

        $contrat = $this->contrat::findorfail($id);
        $contrat->fill(["status" => "ACTIF"])->save();

        return redirect()->route('contrats.index')->with('success', "Statut mis à jour ! ");
    }
}
