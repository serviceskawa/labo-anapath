<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\DetailTestOrder;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Test;
use App\Models\TestOrder;
use App\Models\TypeOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $types_orders = TypeOrder::latest()->get();
        // $types_orders = TypeOrder::orderBy('id', 'desc')->get();
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

        } catch (\Throwable$ex) {

            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    public function show($id)
    {
        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test_order = TestOrder::findorfail($id);
        dd($test_order);

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

        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('examens.details.index', compact(['test_order', 'details', 'tests']));
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
            } catch (\Throwable$th) {
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

        if ($test_order->status) {

            return redirect()->route('test_order.index')->with('success', "   Examen finalisé ! ");

        } else {
            // Ancien algorithme
            // $code = sprintf('%04d', $test_order->id);  "DE22-" . $code
            // dd($code);

            $test_order->fill(["status" => '1', "code" => generateCodeExamen()])->save();

            $report = Report::create([
                "code" => "CO22-" . generateCodeExamen(),
                "patient_id" => $test_order->patient_id,
                "description" => $settings ? $settings->placeholder : '',
                "test_order_id" => $test_order->id,
            ]);

            return redirect()->route('test_order.index')->with('success', "   Examen finalisé ! ");
        }
    }
}
