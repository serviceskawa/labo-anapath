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
use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ContratController extends Controller
{


    protected $contrat;
    protected $setting;
    protected $invoices;
    protected $categoryTest;
    protected $detailsContrat;
    protected $clients;
    protected $tests;


    public function __construct(Contrat $contrat, Setting $setting, Test $tests, CategoryTest $categoryTest, Details_Contrat $detailsContrat, Client $clients, Invoice $invoice)
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

    public function getContratsforDatatable(Request $request)
    {
        $data = $this->contrat->with(['orders', 'client', 'invoices'])->latest();

        return DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('date', function ($data) {
                $date = Carbon::parse($data->created_at);
                return $date->format('d/m/y');
            })

            ->addColumn('name', function ($data) {
                return $data->name;
            })

            ->addColumn('nbre_tests', function ($data) {
                return $data->nbr_tests;
            })

            ->addColumn('status', function ($data) {
                if ($data->is_close == 1) {
                    $reponse = "CLÔTURER";
                } else {
                    $reponse = $data->status;
                }
                return $reponse;
            })

            ->addColumn('action', function ($data) {
                $btn_eyes = '<a type="button" href="' . route('contrat_details.index', $data->id) . '" class="btn btn-warning" title="Voir détails contrat"><i class="mdi mdi-eye"></i></a>';
                $btn_edit_delete = view('contrats.btn_edit_delete', ['data' => $data]);
                return $btn_eyes . ' ' . $btn_edit_delete;
            })

            ->filter(function ($query) use ($request) {

                if (!empty($request->get('statusquery'))) {
                    if ($request->get('statusquery') == "ACTIF") {
                        $query->where('status', "=", "ACTIF");
                    } elseif ($request->get('statusquery') == "INACTIF") {
                        $query->where('status', "=", "INACTIF");
                    } elseif ($request->get('statusquery') == "1") {
                        $query->where('is_close', "=", 0);
                    }
                }

                if (!empty($request->get('cas_status'))) {

                    $query->whereHas('invoices', function ($query) use ($request) {

                        if ($request->get('cas_status') == 1) {
                            $query->where('paid', 1);
                        } elseif ($request->get('cas_status') == 0) {
                            $query->where('paid', 0);
                        }
                    });
                }

                if (!empty($request->get('contenu'))) {
                    $query
                        ->where('name', 'like', '%' . $request->get('contenu') . '%')
                        ->orwhere('description', 'like', '%' . $request->get('contenu') . '%')
                        ->orwhere('type', 'like', '%' . $request->get('contenu') . '%');
                }


                if (!empty($request->get('dateBegin'))) {
                    $newDate = Carbon::createFromFormat('Y-m-d', $request->get('dateBegin'));
                    $query->whereDate('created_at', '>=', $newDate);
                }


                if (!empty($request->get('dateEnd'))) {
                    $query->whereDate('created_at', '<=', $request->get('dateEnd'));
                }
            })
            ->make(true);
    }



    public function index()
    {
        if (!getOnlineUser()->can('view-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        //récupération des contrats avec les détails
        // $contrats = $this->contrat->getWithDetail();
        // $contrats = $this->contrat->latest()->get();
        $clients = $this->clients->latest()->get();

        $setting = $this->setting->find(1);

        config(['app.name' => $setting->titre]);
        return view('contrats.index', compact(['clients']));
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

        //récupérer toutes les categories d'examen
        $tests = Test::all();

        //récupérer les détails d'un contrat
        $details = $this->detailsContrat->where('contrat_id', $contrat->id)->whereNotNull('pourcentage')->get();

        $detail_tests = $this->detailsContrat->where('pourcentage', null)->get();

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('contrats_details.index', compact(['contrat', 'details', 'cateroriesTests', 'tests', 'detail_tests']));
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


        $data = [
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'nbr_tests' => $request->nbr_examen,
            'invoice_unique' => $request->invoice_unique == "on" ? 1 : 0,
            'client_id' => $request->client_id,
            'status' => 'INACTIF',
        ];

        // dd($data['invoice_unique']);

        // dd($request);

        try {
            $contrat = $this->contrat->create($data);
            if ($contrat->invoice_unique == 0) {
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



    public function details_store_test(Request $request)
    {
        if (!getOnlineUser()->can('create-contrats')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $search_test = Test::find(intval($request->test_id));

        // dd($request);
        $contrat = Contrat::findorfail($request->contrat_id);

        try {
            DB::transaction(function () use ($request, $search_test) {
                $detail = new Details_Contrat();
                $detail->contrat_id = intval($request->contrat_id);
                $detail->pourcentage = $request->pourcentage;
                $detail->test_id = intval($request->test_id);
                $detail->amount_remise = intval($request->amount_remise);
                $detail->category_test_id = $search_test->category_test_id;
                $detail->amount_after_remise = $search_test->price - intval($request->amount_remise);
                $detail = $detail->save();
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
        if (!getOnlineUser()->can('edit-contrats')) {
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
