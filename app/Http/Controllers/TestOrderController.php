<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestOrderRequest;
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
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Contracts\DataTable;

class TestOrderController extends Controller
{

    protected $test;
    protected $testOrder;
    protected $doctor;
    protected $report;
    protected $contrat;
    protected $invoice;
    protected $patient;
    protected $setting;
    protected $hospital;
    protected $typeOrder;
    protected $invoiceDetail;
    protected $detailTestOrder;
    protected $logReport;

public function __construct(
    Test $test,
    TestOrder $testOrder,
    Doctor $doctor,
    Report $report,
    Contrat $contrat,
    Invoice $invoice,
    Patient $patient,
    Setting $setting,
    Hospital $hospital,
    TypeOrder $typeOrder,
    InvoiceDetail $invoiceDetail,
    DetailTestOrder $detailTestOrder,
    LogReport $logReport
) {
    $this->middleware('auth');
    $this->test = $test;
    $this->testOrder = $testOrder;
    $this->doctor = $doctor;
    $this->report = $report;
    $this->contrat = $contrat;
    $this->invoice = $invoice;
    $this->patient = $patient;
    $this->setting = $setting;
    $this->hospital = $hospital;
    $this->typeOrder = $typeOrder;
    $this->invoiceDetail = $invoiceDetail;
    $this->detailTestOrder = $detailTestOrder;
    $this->logReport = $logReport;

}
    // Affiche la liste des examens
    public function index()
    {
        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        // Récupération des données nécessaires
        $examens = $this->testOrder->with(['patient', 'contrat', 'type'])->orderBy('id', 'desc')->get();
        $contrats = $this->contrat->all();
        $patients = $this->patient->all();
        $doctors = $this->doctor->all();
        $hopitals = $this->hospital->all();
        $types_orders = $this->typeOrder->all();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        // Affichage de la vue
        return view('examens.index', compact(['examens', 'contrats', 'patients', 'doctors', 'hopitals', 'types_orders']));
    }

    // Utilise yanjra pour le tableau
    public function index2()
    {

        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $examens = $this->testOrder->with(['patient', 'contrat', 'type'])->orderBy('id', 'desc')->get();
        $contrats = $this->contrat->all();
        $patients = $this->patient->all();
        $doctors = $this->doctor->all();
        $hopitals = $this->hospital->all();
        $types_orders = $this->typeOrder->all();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        $testOrders = $this->testOrder->all();
        $testStats = $this->getTestStats($testOrders);

        return view('examens.index2', array_merge(compact('examens', 'contrats', 'patients', 'doctors', 'hopitals', 'types_orders', 'testStats'), [
            'finishTest' => $testStats['finishTest'],
            'noFinishTest' => $testStats['noFinishTest'],
            'is_urgent' => $testStats['is_urgent'],
        ]));
    }

    private function getTestStats($testOrders)
    {
        $noFinishTest = 0;
        $finishTest = 0;
        $is_urgent = 0;

        foreach ($testOrders as $testOrder) {
            if($testOrder->report){
                if ($testOrder->report->is_deliver == 0) {
                    $noFinishTest ++;
                }else {
                    $finishTest++;
                }
            }
            if ($testOrder->is_urgent ==1) {
                $is_urgent++;
            }
        }

        return compact('finishTest', 'noFinishTest', 'is_urgent');
    }

    public function getTestOrders(Request $request)
    {
        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $examens = $this->testOrder->with(['patient']);

        if (!empty($request->date)) {
            $date = explode("-", $request->date);
            $date[0] = str_replace('/', '-', $date[0]);
            $date[1] = str_replace('/', '-', $date[1]);
            $startDate = date("Y-m-d", strtotime($date[0]));
            $endDate = date("Y-m-d", strtotime($date[1]));

            $examens = $examens->whereBetween('created_at', [$startDate, $endDate]);
        }

        if (!empty($request->contrat_id)) {
            $examens = $examens->where('contrat_id', $request->contrat_id);
        }

        if (!is_null($request->exams_status)) {
            $examens = $examens->where('status', $request->exams_status);
        }

        if (!is_null($request->cas_status)) {
            $examens = $examens->where('is_urgent', $request->cas_status);
        }

        $examens = $examens->orderBy('id', 'desc')->get();

        return response()->json($examens);
    }

    // Fonction de selection des demandes d'examen pour select2 ajax
    public function getAllTestOrders(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $test_orders = $this->testOrder->orderby('id', 'desc')->limit(15)->get();
        } else {
            $test_orders = $this->testOrder->orderby('id', 'desc')->where('code', 'like', '%' . $search . '%')->limit(5)->get();
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
        $patients = $this->patient->all();
        $doctors = $this->doctor->all();
        $hopitals = $this->hospital->all();
        $contrats = $this->contrat->ofStatus('ACTIF')->get();
        $types_orders = $this->typeOrder->all();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('examens.create', compact(['patients', 'doctors', 'hopitals', 'contrats', 'types_orders']));
    }

    public function store(TestOrderRequest $request)
    {

        if (!getOnlineUser()->can('create-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $validatedData = [
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'hospital_id' => $request->hospital_id,
            'prelevement_date' => $request->prelevement_date,
            'reference_hopital' => $request->reference_hopital,
            'contrat_id' => $request->contrat_id,
            'is_urgent' => $request->is_urgent,
            'examen_reference_select' => $request->examen_reference_select,
            'examen_reference_input' => $request->examen_reference_input,
            'type_examen' => $request->type_examen,
            'option'=> $request->option?1:0,
        ];

        $contrat = $this->contrat->findOrFail($validatedData['contrat_id']);

        if ($contrat->nbr_tests != -1 && $contrat->orders->count() >= $contrat->nbr_tests) {
            return back()->with('error', "Échec de l'enregistrement. Le nombre d'examen de ce contrat est atteint ");
        }

        $examenFilePath = "";
        if ($request->hasFile('examen_file')) {
            $examenFile = $request->file('examen_file');
            $examenFilePath = $examenFile->store('tests/orders', 'public');
        }

        $doctor = $this->doctor->firstOrCreate(['name' => $validatedData['doctor_id']]);
        $hospital = $this->hospital->firstOrCreate(['name' => $validatedData['hospital_id']]);

        $testAffiliate = $validatedData['examen_reference_select'] ?: $validatedData['examen_reference_input'] ?: "";

        if ($validatedData['examen_reference_select'] && !$validatedData['examen_reference_input']) {
            $testOrder = $this->testOrder->findOrFail($validatedData['examen_reference_select']);
            $testAffiliate = $testOrder->code;
        }

        try {
            $testOrder = new TestOrder([
                'contrat_id' => $validatedData['contrat_id'],
                'patient_id' => $validatedData['patient_id'],
                'hospital_id' => $hospital->id,
                'prelevement_date' => $validatedData['prelevement_date'],
                'doctor_id' => $doctor->id,
                'reference_hopital' => $validatedData['reference_hopital'] ?: "",
                'is_urgent' => $request->has('is_urgent'),
                'examen_file' => $examenFilePath,
                'test_affiliate' => $testAffiliate,
                'option' => $validatedData['option'],
                'type_order_id' => $validatedData['type_examen']
            ]);

            DB::transaction(function () use ($testOrder) {
                $testOrder->save();
            });

            return redirect()->route('details_test_order.index', $testOrder->id);
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    public function edit($id)
    {

        if (!getOnlineUser()->can('edit-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = $this->testOrder->findOrFail($id);

        if (empty($test_order)) {
            return back()->with('error', "Cet examen n'existe. Veuillez réessayer! ");
        }

        $patients = $this->patient->all();
        $doctors = $this->doctor->all();
        $hopitals = $this->hospital->all();
        $contrats = $this->contrat->ofStatus('ACTIF')->get();
        $types_orders = $this->typeOrder->all();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('examens.edit', compact(['test_order', 'patients', 'doctors', 'hopitals', 'contrats', 'types_orders']));
    }

    public function destroy($id)
    {
        if (!getOnlineUser()->can('delete-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $this->testOrder->find($id)->delete();
        return back()->with('success', "    Un élement a été supprimé ! ");
    }

    public function details_index($id)
    {

        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = $this->testOrder->find($id);

        $tests = $this->test->all();

        $details = $this->detailTestOrder->where('test_order_id', $test_order->id)->get();


        $types_orders = $this->typeOrder->all();

        // fusion update et read
        $patients = $this->patient->all();
        $doctors = $this->doctor->all();
        $hopitals = $this->hospital->all();
        $contrats = $this->contrat->ofStatus('ACTIF')->get();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('examens.details.index', compact(['test_order', 'details', 'tests', 'types_orders', 'patients', 'doctors', 'hopitals', 'contrats',]));
    }

    public function getInvoice(Request $request)
    {
        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $invoice = $this->invoice->where('test_order_id', $request->testId)->get();
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

        $test = $this->test->find($data['test_id']);
        $test_order = $this->testOrder->findorfail($data['test_order_id']);

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
        $test_order = $this->testOrder->find($id);
        $tests = $this->test->all();
        $details = $this->detailTestOrder->where('test_order_id', $test_order->id)->get();
        return response()->json($details);
    }

    public function updateTest(Request $request)
    {
        if (!getOnlineUser()->can('edit-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = $this->testOrder->findorfail($request->test_order_id);
        $test = $this->test->findorfail($request->test_id1);
        $row_id = (int)$request->row_id;
        $price = $request->price1;
        $remise = $request->remise1;
        $total = $request->total1;

        //$invoice = $this->invoice->where('test_order_id','=',$request->test_order_id)->get();
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

            return redirect()->route('invoice.show', [$invoice->id])->with('success', " Modification effectuée avec succès  ! ");
        }else{
            return back()->with('error', "Cette opération n'est pas possible car la facture a déjà été payé");
        }
    }

    public function updateTestTotal(Request $request)
    {
        if (!getOnlineUser()->can('edit-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = $this->testOrder->findorfail($request->test_order_id);

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
        $detail = $this->detailTestOrder->findorfail($request->id);
        $detail->delete();
        return response()->json(200);

        // return back()->with('success', "    Un élement a été supprimé ! ");
    }

    public function updateStatus($id)
    {
        if (!getOnlineUser()->can('edit-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = $this->testOrder->findorfail($id);
        $settings = $this->setting->find(1);
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
            $invoice = $this->invoice->create([
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
                    $this->invoiceDetail->create([
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

        $testOrder = $this->testOrder->FindOrFail($id);

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
            'attribuate_doctor_id' => 'nullable',
            'option' => 'nullable',
        ]);

        $contrat = $this->contrat->FindOrFail($data['contrat_id']);

        $path_examen_file = "";

        if ($request->file('examen_file')) {

            $examen_file = time() . '_test_order_.' . $request->file('examen_file')->extension();

            $path_examen_file = $request->file('examen_file')->storeAs('tests/orders', $examen_file, 'public');
        }

        if (is_string($data['doctor_id'])) {
            $doctor = $this->doctor->where('name', $data['doctor_id'])->first();

            $data['doctor_id'] = $doctor->id;
        }
        if (is_string($data['hospital_id'])) {
            $hopital = $this->hospital->where('name', $data['hospital_id'])->first();

            $data['hospital_id'] = $hopital->id;
        }

        $data['test_affiliate'] = "";
        if (empty($request->examen_reference_select) && !empty($request->examen_reference_input)) {

            $data['test_affiliate'] = $request->examen_reference_input;
        } elseif (!empty($request->examen_reference_select) && empty($request->examen_reference_input)) {
            // Recherche l'existance du code selectionner
            $reference = $this->testOrder->findorfail((int) $request->examen_reference_select);

            if (!empty($reference)) {

                $data['test_affiliate'] = $reference->code;
            }
        }

        try {

            $test_order = $this->testOrder->find($id);
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
            $test_order->option = $data['option'];
            $test_order->save();

            $invoice = $test_order->invoice()->first();

            if ($invoice) {
                if ($invoice->paid !=1) {
                    $invoice->fill([
                        "date" => date('Y-m-d'),
                        "patient_id" => $test_order->patient_id,
                        "client_name" => $test_order->patient->firstname . ' ' . $test_order->patient->lastname,
                        "client_address" => $test_order->patient->adresse,
                   ])->save();
                }
                return redirect()->route('invoice.show', [$invoice->id])->with('success', " Modification effectuée avec succès  ! ");
            }else {
                return back()->with('error',"La facture n'existe pas");
            }

        } catch (\Throwable $ex) {

            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    public function getStatus()
    {

    }

    private function getStatusCalling($id)
    {
        $getV = [];
        if ($id) {
            $client = new Client();
            $accessToken = '421|ACJ1pewuLLQKPsB8W59J1ZLoRRDsamQ87qJpVlTLs4h0Rs9D9nfKuBW1usjOuaJjIF77Md18i2kGbz6n840gdZ0vxSZaxbEPM22PLto17kfFQs9Kjt4XyZTBxVwMfp7aTMfaEjqTag6JIROGjZILh1pldzMqvvki7yzWpcMlzylqfZUBh86M1ddCFW0n1wgk3RapG0u2Bf8m7BDABelg7Umv0D0oIpVK4w5gxTuAq29ycUqk';

            //Récupérer tous les appels vocaux
            $response = $client->request('GET', 'https://api.getourvoice.com/v1/calls', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);


            foreach ($data['data'] as $value) {
                if ($value['id'] = $id) {
                    $getV = $value;
                }
            }
            return $getV['status'];
        } else {
            return $getV;
        }

    }

    public function getTestOrdersforDatatable(Request $request)
    {


        $data = $this->testOrder->with(['patient', 'contrat', 'type', 'details', 'report'])->orderBy('created_at', 'desc');

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
                        if (!empty($data->report)) {
                            if($data->report->is_deliver ==1){
                                return 'table-success';
                            }else {
                                if($data->report->status == 1){
                                    return 'table-warning';
                                }
                            }

                        }
                            return 'table-danger urgent';

                }elseif (!empty($data->report)) {
                    if($data->report->is_deliver ==1){
                        return 'table-success';
                    }else {
                        if($data->report->status == 1){
                            return 'table-warning';
                        }
                    }
                }else {
                    return '';
                }
            })

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
                    if ($data->report->status ==1) {
                        // <button type="button" target="_blank" onclick="passwordTest('. $data->report->id.')" class="btn btn-warning" title="Imprimer le compte rendu"><i class="mdi mdi-printer"></i> Imprimer </button>$data->option ?'<i class="uil-calling"></i>':'<i class="mdi mdi-message"></i> '
                        $icon = $data->option ? '<i class="uil-message"></i>':'<i class="uil-calling"></i>';

                        $btnreport = ' <a type="button" target="_blank" href="' . route('report.updateDeliver',  $data->report->id) . '" class="btn btn-warning" title="Imprimer le compte rendu"><i class="mdi mdi-printer"></i> </a> ';
                        $btncalling = ' <a type="button" href="' . route('report.callOrSendSms',  $data->report->id) . '" class="btn btn-warning" title="">'.$icon.'</a> ';
                    }else {
                        $btnreport ="";
                        $btncalling="";
                    }

                } else {
                    $btnreport = "";
                }
                // if ($data->report->is_deliver == 1) {
                //     $btnreport = ' <a type="button" href="' . route('report.updateDeliver',  $data->report->id) . '" class="btn btn-success" title="Livrer"><i class="uil uil-envelope-upload"></i> </a>';
                // } else {
                //     $btnreport = ' <a type="button" href="' . route('report.updateDeliver',  $data->report->id) . '" class="btn btn-warning" title="Livrer"><i class="uil uil-envelope-upload"></i> </a>';
                // }

                return $btnVoir .  $btnReport . $btnInvoice . $btnreport . $btnDelete . $btncalling;
            })
            ->addColumn('appel', function ($data) {
                $status = $this->getStatusCalling($data->status_appel);

                switch ($status) {
                    case 'no-answer':
                        $btn = 'danger';
                        break;
                    case 'answered':
                        $btn = 'success';
                        break;
                    default:
                        $btn = 'warning';
                        break;
                }
                $span = '<div class=" bg-'.$btn.' rounded-circle p-2 col-lg-2" ></div>';
                if (!$data->option) {
                    return $span;
                }
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
            ->addColumn('dropdown', function ($data) {
                $order = $data;
                $setting = $this->setting->find(1);
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
                if(!empty($request->get('contenu')))
                {
                    $query->where('code','like','%'.$request->get('contenu').'%')
                        ->orwhereHas('report', function($query) use ($request){
                        $query->where('description', 'like', '%'.$request->get('contenu').'%');
                            })
                        ->orwhereHas('patient', function ($query) use ($request){
                        $query->where('firstname','like', '%'.$request->get('contenu').'%')
                            ->orwhere('lastname', 'like', '%'.$request->get('contenu').'%');
                            })
                        ->orwhereHas('doctor', function ($query) use ($request){
                            $query->where('name','like','%'.$request->get('contenu').'%');
                        })
                        ->orwhereHas('contrat', function ($query) use ($request){
                            $query->where('name','like', '%'.$request->get('contenu').'%');
                        });
                }

                if(!empty($request->get('dateBegin'))){
                    //dd($request);
                    $newDate = Carbon::createFromFormat('Y-m-d', $request->get('dateBegin'));
                    $query->whereDate('created_at','>',$newDate);
                }
                if(!empty($request->get('dateEnd'))){
                    //dd($request);
                    $query->whereDate('created_at','<',$request->get('dateEnd'));
                }

            })
            ->rawColumns(['action','appel', 'patient', 'contrat', 'details', 'rendu', 'type', 'dropdown'])
            ->make(true);
    }

    public function attribuateDoctor($doctorId, $orderId)
    {
        $testOrder = $this->testOrder->findorfail($orderId);
        $testOrder->fill(["attribuate_doctor_id" => $doctorId])->save();
        return response()->json($doctorId, 200);
    }
}
