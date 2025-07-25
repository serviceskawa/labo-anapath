<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContratDetailRequest;
use App\Http\Requests\ContratRequest;
use App\Models\CategoryTest;
use App\Models\Client;
use App\Models\Contrat;
use App\Models\Details_Contrat;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
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

    public function create_examen_reduction(Contrat $contrat)
    {
        $tests = Test::latest()->get();
        return view('contrats_details.create_examen_reduction', compact('tests', 'contrat'));
    }


    public function edit_examen_reduction(Details_Contrat $detail_contrat)
    {
        $tests = Test::latest()->get();
        return view('contrats_details.edit_examen_reduction', compact('tests', 'detail_contrat'));
    }

    public function update_examen_reduction(Request $request, Details_Contrat $detail_contrat)
    {
        $search_test = Test::find(intval($request->test_id));
        $contrat = Contrat::findorfail($request->contrat_id);
        try {
            // dd(intval($request->test_id));
            $detail_contrat->contrat_id = intval($request->contrat_id);
            $detail_contrat->test_id = intval($request->test_id);
            // dd($detail_contrat->test_id);
            $detail_contrat->amount_remise = intval($request->amount_remise);
            $detail_contrat->category_test_id = $search_test->category_test_id;
            $detail_contrat->amount_after_remise = $search_test->price - intval($request->amount_remise);
            $detail = $detail_contrat->save();
            // dd('ok');
            return redirect()->route('contrat_details.index', $detail_contrat->contrat_id)->with('success', "Element enregistré avec succès ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }


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

            ->addColumn('nbr_tests', function ($data) {
                return $data->orders->count();
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
                        $query->where('is_close', "=", 1);
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
        // $details = $this->detailsContrat->where('contrat_id', $contrat->id)->whereNotNull('pourcentage')->get();

        $detail_tests = $this->detailsContrat->where('pourcentage', null)->where('contrat_id', $id)->get();

        // dd($detail_tests);

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('contrats_details.index', compact(['contrat', 'cateroriesTests', 'tests', 'detail_tests']));
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

        // dd($request, $data['invoice_unique']);

        try {
            // $contrat = $this->contrat->create($data);

            $contrat = Contrat::create([
                'name' => $request->name,
                'type' => $request->type,
                'description' => $request->description,
                'nbr_tests' => $request->nbr_examen,
                'invoice_unique' => $request->invoice_unique == "on" ? 1 : 0,
                'client_id' => $request->client_id,
                'status' => 'INACTIF',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($contrat->invoice_unique == 1) {
                $code_facture = $this->generateCodeFacture();
                // dd($code_facture);
                $invoice = Invoice::create([
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

    private function generateCodeFacture()
    {
        //Récupère le dernier enregistrement de la même année avec un code non null et dont les 4 derniers caractères du code sont les plus grands
        // $invoice = Invoice::whereYear('created_at', '=', now()->year)
        //     ->whereNotNull('code')
        //     ->orderByRaw('RIGHT(code, 4) DESC')
        //     ->first();

        // Si c'est le premier enregistrement ou si la date de l'enregistrement est différente de l'année actuelle, le code sera "0001"
        // if (!$invoice || $invoice->created_at->year != now()->year) {
        //     $code = "0001";
        //     dd($invoice);
        // }
        // Sinon, incrémente le dernier code de 1
        // else {
        //     // Récupère les quatre derniers caractères du code
        //     $lastCode = substr($invoice->code, -4);
        //     dd($invoice);

        //     // Convertit la chaîne en entier et l'incrémente de 1
        //     $code = intval($lastCode) + 1;
        //     $code = str_pad($code, 4, '0', STR_PAD_LEFT);
        // }


        // ===============================================================================================


        // Récupérer tous les enregistrements pour l'année actuelle avec un code non null
        $invoices = Invoice::whereYear('created_at', now()->year)
            ->whereNotNull('code')
            ->orderByRaw('RIGHT(code, 4) DESC')
            ->get();

        // Filtrer pour exclure les codes de type REGULARISATION
        $filteredInvoices = $invoices->filter(function ($invoice) {
            return $invoice->code !== 'REGULARISATION';
        });

        // Si la collection filtrée est vide, cela signifie que soit il n'y a pas de codes, soit tous les codes sont des régularisations
        if ($filteredInvoices->isEmpty()) {
            $code = "0001";
        } else {
            // Récupérer le dernier code valide dans la liste filtrée
            $latestInvoice = $filteredInvoices->first();

            // Récupérer les quatre derniers caractères du code
            $lastCode = substr($latestInvoice->code, -4);

            // Convertir la chaîne en entier et l'incrémenter de 1
            $code = intval($lastCode) + 1;

            // Formater le code avec des zéros à gauche pour qu'il ait toujours 4 chiffres
            $code = str_pad($code, 4, '0', STR_PAD_LEFT);
        }
        // Si le code est au format incorrect ou si le code est REGULARISATION, ajuster la logique ici
        // Exemple pour s'assurer que le code est au format correct
        // (Vérifiez selon votre logique si nécessaire)

        // ============
        // Ajoute les deux derniers chiffres de l'année au début du code
        return "FA" . now()->year % 100 . "$code";
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
        $contrat = Contrat::findorfail($request->contrat_id);

        try {

            DB::transaction(function () use ($request, $search_test) {
                $detail = new Details_Contrat();
                $detail->contrat_id = intval($request->contrat_id);
                $detail->pourcentage = $request->pourcentage;
                $detail->test_id = intval($request->test_id);
                $detail->amount_remise = intval($request->remise);
                $detail->category_test_id = $search_test->category_test_id;
                $detail->amount_after_remise = intval($request->total);
                $detail = $detail->save();

                // Recuperées le la ligne du invoice_id
                // $invoice = Invoice::where('contrat_id', $request->contrat_id)->first();
                // $invoice_detail = new InvoiceDetail();
                // $invoice_detail->invoice_id = $invoice->id;
                // $invoice_detail->test_name =  $search_test->name;
                // $invoice_detail->test_id = $request->id;
                // $invoice_detail->price = $request->price;
                // $invoice_detail->discount =  $request->remise;
                // $invoice_detail->total = $request->total;
                // $invoice_detail = $invoice_detail->save();

                // // Update facture
                // $invoice = Invoice::findOrFail($invoice->id);
                // $invoice->discount = $invoice->discount + $request->remise;
                // $invoice->subtotal = $invoice->total + $request->total;
                // $invoice->total = $invoice->total + $invoice->subtotal;
                // $invoice->save();
            });

            return redirect()->route('contrat_details.index', $request->contrat_id)->with('success', "Element enregistré avec succès ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    public function delete_detail($detail_contrat)
    {
        // Rechercher le détail du contrat
        $detail = $this->detailsContrat->find($detail_contrat);

        // Vérifier si le détail du contrat a été trouvé
        if ($detail) {
            // Tenter de supprimer le détail du contrat
            try {
                $detail->delete();
                // Rediriger avec un message de succès
                return back()->with('success', "Élément supprimé avec succès !");
            } catch (\Exception $e) {
                // Capturer les exceptions et afficher un message d'erreur
                return back()->with('error', "Erreur lors de la suppression : " . $e->getMessage());
            }
        } else {
            // Rediriger avec un message d'erreur si le détail n'a pas été trouvé
            return back()->with('error', "Élément non trouvé !");
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
            'status' => $request->status,
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