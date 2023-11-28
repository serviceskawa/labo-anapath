<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\DetailTestOrder;
use App\Models\Doctor;
use App\Models\Employee;
use App\Models\Hospital;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\LogReport;
use App\Models\Patient;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Test;
use App\Models\test_pathology_macro;
use App\Models\TestOrder;
use App\Models\TypeOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class TestPathologyMacroController extends Controller
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
    protected $employees;
    protected $macro;

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
    LogReport $logReport,
    Employee $employee,
    test_pathology_macro $macro
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
    $this->employees = $employee;
    $this->macro = $macro;

}

    //
    public function index()
    {

        // if (!getOnlineUser()->can('view-test-orders')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }


        $orders = $this->testOrder->all();
        $employees = $this->employees->all();


        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        // $testOrders = $this->testOrder->all();

        // foreach ($testOrders as $key => $testOrder) {
        //     if (!empty($testOrder->attribuate_doctor_id) && empty($testOrder->assigned_to_user_id)) {
        //         $testOrder->assigned_to_user_id = $testOrder->attribuate_doctor_id;
        //         $testOrder->save();
        //     }
        // }

        return view('macro.index', array_merge(compact('orders', 'employees')));
    }

    // Debut
    public function getTestOrdersforDatatable(Request $request)
    {


        $data = $this->macro->with(['order','employee','user'])->orderBy('created_at', 'desc');

        return DataTables::of($data)->addIndexColumn()

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
               $btnDelete = ' <button type="button" onclick="deleteModal(' . $data->id . ')" class="btn btn-danger" title="Supprimer"><i class="mdi mdi-trash-can-outline"></i> </button>';

                return !isAffecte($data->order->id) ? $btnDelete :'';
            })
            ->addColumn('code', function ($data) {
                return $data->order->code;
            })
            ->addColumn('add_by', function ($data) {
                return $data->user->fullname();
            })
            ->addColumn('assign_to', function ($data) {

                return isAffecte($data->order->id) ? isAffecte($data->order->id)->fullname() :'';
            })
            ->addColumn('status', function ($data) {
                if (!empty($data->order->report)) {
                    // $btn = $data->getReport($data->id);
                    switch ($data->order->report->status) {
                        case 1:
                            $btn = '<div class="badge bg-success px-2 text-success rounded-pill">...</div>';
                            break;

                        default:
                        $btn = '<div class="badge bg-warning px-2 text-warning rounded-pill">...</div>';;
                            break;
                    }
                } else {
                    $btn = 'Non enregistré';
                }
                $span = $btn;
                return $span;
            })
            ->filter(function ($query) use ($request,$data) {

                if (!empty($request->get('id_test_pathology_order'))) {
                    $query->where('id_test_pathology_order', $request->get('id_test_pathology_order'));
                }
                if (!empty($request->get('id_employee'))) {
                    $query->where('id_employee', $request->get('id_employee'));
                }

                if(!empty($request->get('date'))){
                    //dd($request);
                    $query->whereDate('created_at','like',$request->get('date'));
                }

            })
            ->rawColumns(['action','code', 'add_by', 'assign_to', 'status'])
            ->make(true);
    }

    public function create() {
        $orders = $this->testOrder->all();
        $employees = $this->employees->all();
        return view('macro.create', array_merge(compact('orders', 'employees')));
    }

    public function store(Request $request) {
        // dd($request);

        $orders = $request->orders;
        foreach ($orders as $key => $order) {
            # code...
            $macro = new test_pathology_macro();
            $macro->id_employee = $request->id_employee;
            $macro->date = $request->date;
            $macro->id_test_pathology_order = $order;
            $macro->user_id = Auth::user()->id;
            $macro->save();
        }

        return redirect()->route('macro.index')->with('sucess', "Enregistrement effectué avec succès");
    }

    public function update(Request $request) {
        $orders = $this->testOrder->all();
        $employees = $this->employees->all();
        return view('macro.create', array_merge(compact('orders', 'employees')));
    }
    public function destroy($id) {

        $macro = $this->macro->find($id);
        $macro->delete();
        return redirect()->route('macro.index')->with('sucess', "Enregistrement effectué avec succès");
    }
}
