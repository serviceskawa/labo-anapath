<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Test;
use App\Models\Doctor;
use App\Models\Report;
use App\Models\Contrat;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Setting;
use App\Models\Hospital;
use App\Models\TestOrder;
use App\Models\TypeOrder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\DetailTestOrder;
use App\Models\LogReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestOrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $examens = TestOrder::with(['patient', 'contrat', 'type'])->orderBy('id', 'desc')->get();
        $contrats = Contrat::all();
        $patients = Patient::all();
        $doctors = Doctor::all();
        //$tests = Test::all();
        $hopitals = Hospital::all();
        $types_orders = TypeOrder::all();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        // dd($examens);
        return view('examens.index', compact(['examens', 'contrats', 'patients', 'doctors', 'hopitals', 'types_orders']));
    }

    // Utilise yanjra pour le tableau
    public function index2()
    {

        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $examens = TestOrder::with(['patient', 'contrat', 'type'])->orderBy('id', 'desc')->get();
        $contrats = Contrat::all();
        $patients = Patient::all();
        $doctors = Doctor::all();
        //$tests = Test::all();
        $hopitals = Hospital::all();
        $types_orders = TypeOrder::all();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        // dd($examens);
        return view('examens.index2', compact(['examens', 'contrats', 'patients', 'doctors', 'hopitals', 'types_orders']));
    }

    // Fonction de recherche
    public function getTestOrders(Request $request)
    {
        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        if (empty($request->date)) {
            $examens = TestOrder::with(['patient'])->orderBy('id', 'desc')->get();
        } else {
            $date = explode("-", $request->date);
            $date[0] = str_replace('/', '-', $date[0]);
            $date[1] = str_replace('/', '-', $date[1]);
            $startDate = date("Y-m-d", strtotime($date[0]));
            $endDate = date("Y-m-d", strtotime($date[1]));

            $examens = TestOrder::whereBetween('created_at', [$startDate, $endDate])->with(['patient']);

            if (!empty($request->contrat_id)) {
                $examens = $examens->where('contrat_id', $request->contrat_id);
            }

            if (is_null($request->exams_status)) {
                $examens = $examens;
            } else {
                $examens = $examens->where('status', $request->exams_status);
            }

            if (is_null($request->cas_status)) {
                $examens = $examens;
            } else {
                $examens = $examens->where('is_urgent', $request->cas_status);
            }

            $examens = $examens->orderBy('id', 'desc')->get();
        }

        return response()->json($examens);
    }

    // Fonction de selection des demandes d'examen pour select2 ajax
    public function getAllTestOrders(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $test_orders = TestOrder::orderby('id', 'desc')->limit(15)->get();
        } else {
            $test_orders = TestOrder::orderby('id', 'desc')->where('code', 'like', '%' . $search . '%')->limit(5)->get();
        }

        $response = array();
        foreach ($test_orders as $test_order) {
            $response[] = array(
                "id" => $test_order->id,
                "text" => $test_order->code,
            );
        }
        return response()->json($response);
    }

    public function create()
    {

        if (!getOnlineUser()->can('create-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $patients = Patient::all();
        $doctors = Doctor::all();
        $hopitals = Hospital::all();
        $contrats = Contrat::ofStatus('ACTIF')->get();
        $types_orders = TypeOrder::all();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('examens.create', compact(['patients', 'doctors', 'hopitals', 'contrats', 'types_orders']));
    }

    public function store(request $request)
    {

        if (!getOnlineUser()->can('create-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $data = $this->validate($request, [
            'patient_id' => 'required',
            'doctor_id' => 'required|exists:doctors,name', // verification de l'existance du nom envoyé depuis le select
            'hospital_id' => 'required|exists:hospitals,name',
            'prelevement_date' => 'required',
            'reference_hopital' => 'nullable',
            'contrat_id' => 'required',
            'is_urgent' => 'nullable',
            'examen_reference_select' => 'nullable',
            'examen_reference_input' => 'nullable',
            'type_examen' => 'required|exists:type_orders,id',
        ]);

        $contrat = Contrat::FindOrFail($data['contrat_id']);

        if ($contrat) {
        }

        // Verifie si le nombre de test du contrat est différent de -1 et si le nombre de contrat enregistré est inferieur ou egale au nombre de test de contrat

        if ($contrat->nbr_tests != -1 && $contrat->orders->count() <= $contrat->nbr_tests) {
            return back()->with('error', "Échec de l'enregistrement. Le nombre d'examen de ce contrat est atteint ");
        }

        $path_examen_file = "";

        if ($request->file('examen_file')) {

            $examen_file = time() . '_test_order_.' . $request->file('examen_file')->extension();

            $path_examen_file = $request->file('examen_file')->storeAs('tests/orders', $examen_file, 'public');
        }

        if (is_string($data['doctor_id'])) {
            $doctor = Doctor::where('name', $data['doctor_id'])->first();

            $data['doctor_id'] = $doctor->id;
        }
        if (is_string($data['hospital_id'])) {
            $hopital = Hospital::where('name', $data['hospital_id'])->first();

            $data['hospital_id'] = $hopital->id;
        }

        $data['test_affiliate'] = "";
        if (empty($request->examen_reference_select) && !empty($request->examen_reference_input)) {

            $data['test_affiliate'] = $request->examen_reference_input;
        } elseif (!empty($request->examen_reference_select) && empty($request->examen_reference_input)) {
            // Recherche l'existance du code selectionner
            $reference = TestOrder::findorfail((int) $request->examen_reference_select);

            if (!empty($reference)) {

                $data['test_affiliate'] = $reference->code;
            }
        }

        try {

            $test_order = new TestOrder();
            DB::transaction(function () use ($data, $test_order, $request, $path_examen_file) {
                $test_order->contrat_id = $data['contrat_id'];
                $test_order->patient_id = $data['patient_id'];
                $test_order->hospital_id = $data['hospital_id'];
                $test_order->prelevement_date = $data['prelevement_date'];
                $test_order->doctor_id = $data['doctor_id'];
                $test_order->reference_hopital = $data['reference_hopital'];
                $test_order->is_urgent = $request->is_urgent ? 1 : 0;
                $test_order->examen_file = $request->file('examen_file') ? $path_examen_file : "";
                $test_order->test_affiliate = $data['test_affiliate'] ? $data['test_affiliate'] : "";
                $test_order->type_order_id = $data['type_examen'];
                $test_order->save();
            });

            return redirect()->route('details_test_order.index', $test_order->id);
        } catch (\Throwable $ex) {

            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    public function show($id)
    {
        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = TestOrder::findorfail($id);
        // dd($test_order);
    }

    public function edit($id)
    {

        if (!getOnlineUser()->can('edit-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = TestOrder::findOrFail($id);

        if (empty($test_order)) {
            return back()->with('error', "Cet examen n'existe. Veuillez réessayer! ");
        }

        $patients = Patient::all();
        $doctors = Doctor::all();
        $hopitals = Hospital::all();
        $contrats = Contrat::ofStatus('ACTIF')->get();
        $types_orders = TypeOrder::all();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('examens.edit', compact(['test_order', 'patients', 'doctors', 'hopitals', 'contrats', 'types_orders']));
    }

    public function destroy($id)
    {
        if (!getOnlineUser()->can('delete-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = TestOrder::find($id)->delete();
        return back()->with('success', "    Un élement a été supprimé ! ");
    }

    public function details_index($id)
    {

        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = TestOrder::find($id);

        $tests = Test::all();

        $details = DetailTestOrder::where('test_order_id', $test_order->id)->get();


        $types_orders = TypeOrder::all();

        // fusion update et read
        $patients = Patient::all();
        $doctors = Doctor::all();
        $hopitals = Hospital::all();
        $contrats = Contrat::ofStatus('ACTIF')->get();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('examens.details.index', compact(['test_order', 'details', 'tests', 'types_orders', 'patients', 'doctors', 'hopitals', 'contrats',]));
    }

    public function getInvoice(Request $request)
    {
        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $invoice = Invoice::where('test_order_id', $request->testId)->get();
        return response()->json($invoice);
    }

    public function details_store(Request $request)
    {
        if (!getOnlineUser()->can('create-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->validate($request, [
            'test_order_id' => 'required',
            'test_id' => 'required',
            'price' => 'required |numeric',
            'discount' => 'required |numeric',
            'total' => 'required |numeric',
        ]);

        $test = Test::find($data['test_id']);
        $test_order = TestOrder::findorfail($data['test_order_id']);

        $test_order_exit = $test_order->details()->whereTestId($data['test_id'])->exists();

        if ($test_order_exit) {
            return response()->json(['success' => "Examin deja ajouté"]);
        } else {
            try {
                DB::transaction(function () use ($data, $test) {
                    $details = new DetailTestOrder();
                    $details->test_id = $data['test_id'];
                    $details->test_name = $test->name;
                    $details->price = $data['price'];
                    $details->discount = $data['discount'];
                    $details->total = $data['total'];
                    $details->test_order_id = $data['test_order_id'];
                    $details->save();
                });

                //  return back()->with('success', "Opération effectuée avec succès ! ");
                return response()->json(200);
            } catch (\Throwable $th) {
                return response()->json(200);
            }
        }
    }

    public function getDetailsTest($id)
    {
        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = TestOrder::find($id);
        $tests = Test::all();
        $details = DetailTestOrder::where('test_order_id', $test_order->id)->get();
        return response()->json($details);
    }

    public function updateTest(Request $request)
    {
        if (!getOnlineUser()->can('edit-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = TestOrder::findorfail($request->test_order_id);
        $test = Test::findorfail($request->test_id1);
        $row_id = (int)$request->row_id;
        $price = $request->price1;
        $remise = $request->remise1;
        $total = $request->total1;

        //$invoice = Invoice::where('test_order_id','=',$request->test_order_id)->get();
        $invoice = $test_order->invoice()->first();
        $invoiceDetails = $invoice->details()->get();
        //dd($invoice);
        $details = $test_order->details()->get();
        //dd($details[$row_id]);
        $detail = $details[$row_id];
        $invoiceDetail = $invoiceDetails[$row_id];
        if ($invoice->paid !=1) {
            $detail->fill([
                "test_id" =>  $request->test_id1,
                "test_name" =>  $test->name,
                "price" => $test->price,
                "discount" => $remise,
                "total" => $total,
            ])->save();
           $invoice->fill([
                "subtotal" =>$test_order->subtotal,
                "discount" =>$test_order->discount,
                "total" =>$test_order->total,
           ])->save();
            $invoiceDetail->fill([
                "test_id" =>  $request->test_id1,
                "test_name" =>  $test->name,
                "price" => $test->price,
                "discount" => $remise,
                "total" => $total,
            ])->save();

            return back()->with('success', "Mis à jour de la demande éffectué");
        }else{
            return back()->with('error', "Cette opération n'est pas possible car la facture a déjà été payé");
        }
    }

    public function updateTestTotal(Request $request)
    {
        if (!getOnlineUser()->can('edit-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = TestOrder::findorfail($request->test_order_id);

        $test_order->fill([
            "total" => $request->total,
            "subtotal" => $request->subTotal,
            "discount" => $request->discount,
        ])->save();

        return response()->json($test_order);
    }

    public function details_destroy(Request $request)
    {
        if (!getOnlineUser()->can('delete-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $detail = DetailTestOrder::findorfail($request->id);
        $detail->delete();
        return response()->json(200);

        // return back()->with('success', "    Un élement a été supprimé ! ");
    }

    public function updateStatus($id)
    {
        if (!getOnlineUser()->can('edit-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = TestOrder::findorfail($id);
        $settings = Setting::find(1);
        $user = Auth::user();

        if ($test_order->status) {

            return redirect()->route('test_order.index')->with('success', "   Examen finalisé ! ");
        } else {

            // Génère un code unique
            $code_unique = generateCodeExamen();

            $test_order->fill(["status" => '1', "code" => $code_unique])->save();

            $report = Report::create([
                "code" => "CO" . $test_order->code,
                "patient_id" => $test_order->patient_id,
                "description" => $settings ? $settings->placeholder : '',
                "test_order_id" => $test_order->id,
            ]);
            $reportnow = Report::latest()->first();

            $log = new LogReport();
            $log->operation = "Créer un nouveau report";
            $log->report_id = $reportnow->id;
            $log->user_id = $user->id;
            $log->save();

            $code_facture = generateCodeFacture();

            // Creation de la facture
            $invoice = Invoice::create([
                "test_order_id" => $test_order->id,
                "date" => date('Y-m-d'),
                "patient_id" => $test_order->patient_id,
                "client_name" => $test_order->patient->firstname . ' ' . $test_order->patient->lastname,
                "client_address" => $test_order->patient->adresse,
                "subtotal" => $test_order->subtotal,
                "discount" => $test_order->discount,
                "total" => $test_order->total,
                "code" => $code_facture,
            ]);
            // Recupération des details de la demande d'examen
            $tests = $test_order->details()->get();

            if (!empty($invoice)) {
                // Creation des details de la facture
                foreach ($tests as $value) {
                    InvoiceDetail::create([
                        "invoice_id" => $invoice->id,
                        "test_id" => $value->test_id,
                        "test_name" => $value->test_name,
                        "price" => $value->price,
                        "discount" => $value->discount,
                        "total" => $value->total,
                    ]);
                }
            }

            return redirect()->route('invoice.show', [$invoice->id])->with('success', " Opération effectuée avec succès  ! ");
        }
    }

    public function update(request $request, $id)
    {

        if (!getOnlineUser()->can('edit-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $testOrder = TestOrder::FindOrFail($id);

        if (empty($testOrder)) {
            return back()->with('error', "Erreur, cette demande d'examen n'existe pas ");
        }

        $data = $this->validate($request, [
            'patient_id' => 'required',
            'doctor_id' => 'nullable', // verification de l'existance du nom envoyé depuis le select
            'hospital_id' => 'required|exists:hospitals,name',
            'prelevement_date' => 'required',
            'reference_hopital' => 'nullable',
            'contrat_id' => 'required',
            'is_urgent' => 'nullable',
            'examen_reference_select' => 'nullable',
            'examen_reference_input' => 'nullable',
            'type_examen' => 'required|exists:type_orders,id',
            'attribuate_doctor_id' => 'required|exists:users,id',
        ]);

        $contrat = Contrat::FindOrFail($data['contrat_id']);

        // Verifie si le nombre de test du contrat est différent de -1 et si le nombre de contrat enregistré est inferieur ou egale au nombre de test de contrat
        // Ici nous ne mettons pas à jour le contrat.
        // if ($contrat->nbr_tests != -1 && $contrat->orders->count() <= $contrat->nbr_tests) {
        //     return back()->with('error', "Échec de l'enregistrement. Le nombre d'examen de ce contrat est atteint ");
        // }

        $path_examen_file = "";

        if ($request->file('examen_file')) {

            $examen_file = time() . '_test_order_.' . $request->file('examen_file')->extension();

            $path_examen_file = $request->file('examen_file')->storeAs('tests/orders', $examen_file, 'public');
        }

        if (is_string($data['doctor_id'])) {
            $doctor = Doctor::where('name', $data['doctor_id'])->first();

            $data['doctor_id'] = $doctor->id;
        }
        if (is_string($data['hospital_id'])) {
            $hopital = Hospital::where('name', $data['hospital_id'])->first();

            $data['hospital_id'] = $hopital->id;
        }

        $data['test_affiliate'] = "";
        if (empty($request->examen_reference_select) && !empty($request->examen_reference_input)) {

            $data['test_affiliate'] = $request->examen_reference_input;
        } elseif (!empty($request->examen_reference_select) && empty($request->examen_reference_input)) {
            // Recherche l'existance du code selectionner
            $reference = TestOrder::findorfail((int) $request->examen_reference_select);

            if (!empty($reference)) {

                $data['test_affiliate'] = $reference->code;
            }
        }

        try {

            $test_order = TestOrder::find($id);
            $test_order->contrat_id = $data['contrat_id']; // on peut modifier le contrat
            $test_order->patient_id = $data['patient_id'];
            $test_order->hospital_id = $data['hospital_id'];
            $test_order->prelevement_date = $data['prelevement_date'];
            $test_order->doctor_id = $data['doctor_id'];
            $test_order->reference_hopital = $data['reference_hopital'];
            $test_order->is_urgent = $request->is_urgent ? 1 : 0;
            $test_order->examen_file = $request->file('examen_file') ? $path_examen_file : "";
            $test_order->test_affiliate = $data['test_affiliate'] ? $data['test_affiliate'] : "";
            $test_order->type_order_id = $data['type_examen'];
            $test_order->attribuate_doctor_id = $data['attribuate_doctor_id'];
            $test_order->save();

            return redirect()->route('details_test_order.index', $test_order->id)->with('success', "Demande d'examen a été mis à jour ! ");
        } catch (\Throwable $ex) {

            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    public function getTestOrdersforDatatable(Request $request)
    {

        $data = TestOrder::with(['patient', 'contrat', 'type', 'details', 'report'])->orderBy('created_at', 'desc');

        return Datatables::of($data)->addIndexColumn()
            ->editColumn('created_at', function ($data) {
                //change over here
                //return date('y/m/d',$data->created_at);
                return $data->created_at;
            })
            ->setRowData([
                'data-mytag' => function ($data) {
                    if ($data->is_urgent == 1) {
                        $result = $data->is_urgent;
                    } else {
                        $result = "";
                    }

                    return 'mytag=' . $result;
                },
            ])
            ->setRowClass(function ($data) use ($request) {
                if($data->is_urgent == 1){
                    if ($request->get('exams_status') == "livrer") {
                        return 'table-success urgent';
                    }elseif (!empty($data->report)) {
                        if($data->report->status == 1){
                            return 'table-warning urgent';
                        }
                    }
                    return 'table-danger urgent';
                }elseif ($request->get('exams_status') == "livrer") {
                    return 'table-success';
                }elseif (!empty($data->report)) {
                    if($data->report->status == 1){
                        return 'table-warning';
                    }
                }else {
                    return '';
                }
            })
            // ->addColumn('examen_file', function ($data) {
            //     //change over
            //     if (!empty($data->examen_file)) {
            //         $btn = '<a href="' . Storage::url($data->examen_file) . '" class="btn btn-primary btn-sm" target="_blank"  rel="noopener noreferrer" type="button"><i class="mdi mdi-cloud-download"></i></a>';
            //     } else {
            //         $btn = 'Aucun fichier';
            //     }
            //     return $btn;
            // })
            ->addColumn('action', function ($data) {
                $btnVoir = '<a type="button" href="' . route('details_test_order.index', $data->id) . '" class="btn btn-primary" title="Voir les détails"><i class="mdi mdi-eye"></i></a>';
                // $btnEdit = ' <a type="button" href="' . route('test_order.edit', $data->id) . '" class="btn btn-primary" title="Mettre à jour examen"><i class="mdi mdi-lead-pencil"></i></a>';
                if ($data->status != 1) {
                    $btnReport = ' <a type="button" href="' . route('details_test_order.index', $data->id) . '" class="btn btn-warning" title="Compte rendu"><i class="uil-file-medical"></i> </a>';
                    $btnDelete = ' <button type="button" onclick="deleteModal(' . $data->id . ')" class="btn btn-danger" title="Supprimer"><i class="mdi mdi-trash-can-outline"></i> </button>';
                    $btnreport = "";
                } else {
                    $btnReport = ' <a type="button" href="' . route('report.show', $data->report->id) . '" class="btn btn-warning" title="Compte rendu"><i class="uil-file-medical"></i> </a>';
                    $btnDelete = "";
                }

                if (!empty($data->invoice->id)) {
                    $btnInvoice = ' <a type="button" href="' . route('invoice.show', $data->invoice->id) . '" class="btn btn-success" title="Facture"><i class="mdi mdi-printer"></i> </a>';
                } else {
                    $btnInvoice = ' <a type="button" href="' . route('invoice.storeFromOrder', $data->id) . '" class="btn btn-success" title="Facture"><i class="mdi mdi-printer"></i> </a>';
                }

                if (!empty($data->report)) {
                    $btnreport = ' <a type="button" target="_blank" href="' . route('report.updateDeliver',  $data->report->id) . '" class="btn btn-warning" title="Imprimer le compte rendu"><i class="mdi mdi-printer"></i> Imprimer </a>';
                    // switch ($data->report->is_deliver) {
                    //     //<a type="button" href="' . route('report.updateDeliver',  $data->report->id) . '" class="btn btn-success" title="Cliquer pour marquer comme non livré"><i class="uil uil-envelope-upload"></i> Marquer comme Non Livrer</a>
                    //     case 1:
                    //         $btnreport = '';
                    //         break;

                    //     default:
                    //         break;
                    // }
                } else {
                    $btnreport = "";
                }
                // if ($data->report->is_deliver == 1) {
                //     $btnreport = ' <a type="button" href="' . route('report.updateDeliver',  $data->report->id) . '" class="btn btn-success" title="Livrer"><i class="uil uil-envelope-upload"></i> </a>';
                // } else {
                //     $btnreport = ' <a type="button" href="' . route('report.updateDeliver',  $data->report->id) . '" class="btn btn-warning" title="Livrer"><i class="uil uil-envelope-upload"></i> </a>';
                // }

                return $btnVoir .  $btnReport . $btnInvoice . $btnreport . $btnDelete;
            })
            ->addColumn('patient', function ($data) {
                return $data->patient->firstname . ' ' . $data->patient->lastname;
            })
            ->addColumn('contrat', function ($data) {
                return $data->contrat->name;
            })
            ->addColumn('details', function (TestOrder $testOrder) {
                $a = $testOrder->details->map(function ($detail) {
                    return Str::limit($detail->test_name, 30, '...');
                    // return '<strong>' . $detail->order->type->title . '</strong>: ' . Str::limit($detail->test_name, 30, '...');
                })->implode('<br>');
                return '<strong>' . $testOrder->type->title . '</strong>: ' . $a;
            })
            ->addColumn('rendu', function ($data) {
                if (!empty($data->report)) {
                    // $btn = $data->getReport($data->id);
                    switch ($data->report->status) {
                        case 1:
                            $btn = 'Valider';
                            break;

                        default:
                            $btn = 'En attente';
                            break;
                    }
                } else {
                    $btn = 'Non enregistré';
                }
                $span = '<span class="badge bg-primary rounded-pill">' . $btn . '</span>';
                return $span;
            })
            ->addColumn('type', function ($data) {
                return $data->type->title;
            })
            ->addColumn('urgence', function ($data) {
                return $data->is_urgent;
            })
            // ->addColumn('dropdown', function ($data) {
            //     $users = getUsersByRole("docteur");
            //     $dropdown = "<select name='doctor_id' class='form-control'>
            //         <option value=''>Tous les contrats</option>";
            //     $endDrop = "</select>";
            //     $a = $users->map(function ($user) {
            //         return "<option value='$user->id'>$user->email</option>";
            //     });
            //     return $dropdown . $a . $endDrop;
            // })
            ->addColumn('dropdown', function ($data) {
                $order = $data;
                $setting = Setting::find(1);
                config(['app.name' => $setting->titre]);
                return view('examens.datatables.attribuate', compact('order'));
            })
            ->filter(function ($query) use ($request) {

                if (!empty($request->get('attribuate_doctor_id'))) {
                    $query->where('attribuate_doctor_id', $request->get('attribuate_doctor_id'));
                }
                if (!empty($request->get('cas_status'))) {
                    $query->where('is_urgent', $request->get('cas_status'));
                }
                if (!empty($request->get('contrat_id'))) {
                    $query->where('contrat_id', $request->get('contrat_id'));
                }
                if (!empty($request->get('type_examen'))) {
                    $query->where('type_order_id', $request->get('type_examen'));
                }
                if (!empty($request->get('exams_status'))) {
                    if ($request->get('exams_status') == "livrer") {
                        $query->whereHas('report', function ($query) {
                            $query->where('is_deliver', 1);
                        });
                    } elseif ($request->get('exams_status') == "non_livrer") {
                        $query->whereHas('report', function ($query) {
                            $query->where('is_deliver', 0);
                        });
                    } else {
                        $query->whereHas('report', function ($query) use ($request) {
                            $query->where('status', $request->get('exams_status'));
                        });
                        // $query->where('status', $request->get('exams_status'));
                    }
                }
            })
            ->rawColumns(['action', 'patient', 'contrat', 'details', 'rendu', 'type', 'dropdown'])
            ->make(true);
    }

    public function attribuateDoctor($doctorId, $orderId)
    {
        $testOrder = TestOrder::findorfail($orderId);
        $testOrder->fill(["attribuate_doctor_id" => $doctorId])->save();
        return response()->json($doctorId, 200);
    }
}
