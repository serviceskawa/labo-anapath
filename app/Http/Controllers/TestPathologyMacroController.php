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
use Illuminate\Support\Facades\DB;
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

        $results = DB::table('test_orders')
            ->select(
                'test_orders.id as test_order',
                'test_orders.code as code',
                'test_orders.created_at',
                'test_orders.is_urgent',
                'reports.status as report_status',
                'test_pathology_macros.id as test_pathology_macro_id'
            )
            ->join('reports', 'test_orders.id', '=', 'reports.test_order_id')
            ->leftJoin('test_pathology_macros', 'reports.id', '=', 'test_pathology_macros.id_test_pathology_order')
            ->where(function ($query) {
                $query->where('test_orders.is_urgent', 1)
                    ->where('reports.status', 0)
                    ->whereNotExists(function ($subquery) {
                        $subquery->select(DB::raw(1))
                                ->from('test_pathology_macros')
                                ->whereRaw('id_test_pathology_order = test_orders.id');
                    });
            })
            ->orWhere(function ($query) {
                $query->where('reports.status', 0)
                    ->whereNotExists(function ($subquery) {
                        $subquery->select(DB::raw(1))
                                ->from('test_pathology_macros')
                                ->whereRaw('id_test_pathology_order = test_orders.id');
                    })
                    ->whereRaw('DATE_ADD(test_orders.created_at, INTERVAL 10 DAY) <= DATE(NOW() + INTERVAL 1 DAY)');
            })
            ->whereYear('test_orders.created_at', '!=', 2022)
            ->orderBy('test_orders.created_at')
            ->get();


        // $testOrders = $this->testOrder->all();

        // foreach ($testOrders as $key => $testOrder) {
        //     if (!empty($testOrder->attribuate_doctor_id) && empty($testOrder->assigned_to_user_id)) {
        //         $testOrder->assigned_to_user_id = $testOrder->attribuate_doctor_id;
        //         $testOrder->save();
        //     }
        // }

        return view('macro.index', array_merge(compact('orders', 'employees', 'results')));
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

            ->addColumn('created', function ($data) {
                $checkbox = "
                 <div class='form-check'>
                     <input type='checkbox' class='form-check-input'id='custom".$data->test_order."'>
                     // <input type='checkbox' class='form-check-input'id='custom".$data->test_order."'>
                 </div>
                ";
                return $checkbox;
             })

            ->addColumn('action', function ($data) {
               $btnDelete = ' <button type="button" onclick="deleteModal(' . $data->id . ')" class="btn btn-danger" title="Supprimer"><i class="mdi mdi-trash-can-outline"></i> </button>';

                return !isAffecte($data->order->id) ? $btnDelete :'';
            })
            ->addColumn('code', function ($data) {
                return $data->order->code;
            })
            ->addColumn('add_by', function ($data) {
                return $data->employee->fullname();
            })
            ->addColumn('date_macro', function ($data) {
                return dateFormat($data->created_at);
            })
            ->addColumn('date_montage', function ($data) {
                if ($data->mounting) {

                    return dateFormat($data->updated_at);
                }
            })
            ->addColumn('state', function ($data) {
                $select = "
                    <ul>
                        " . ($data->circulation ? '<li> <span class="badge bg-primary rounded-pill">Circulation</span></li>' : '') . "
                        " . ($data->embedding ? '<li><span class="badge bg-primary rounded-pill">Enrobage</span></li>' : '') . "
                        " . ($data->microtomy_spreading ? '<li><span class="badge bg-primary rounded-pill">Microtomie et Etalement</span></li>' : '') . "
                        " . ($data->staining ? '<li><span class="badge bg-primary rounded-pill">Coloration</span></li>' : '') . "
                        " . ($data->mounting ? '<li><span class="badge bg-primary rounded-pill">Montage</span></li>' : '') . "
                    </ul>";

                if (!$data->mounting) {
                    // Utilisation de htmlspecialchars pour échapper les caractères spéciaux
                    $escapedCode = htmlspecialchars($data->order->code, ENT_QUOTES, 'UTF-8');
                    $select .= "
                        <select name='id_test_pathology_order".$data->id."' onchange='changeState(".$data->id.",\"".$escapedCode."\")' id='id_test_pathology_order".$data->id."' class='form-select select2' data-toggle='select2'>
                            <option value=''>Sélectionner un étape</option>";

                    if (!$data->circulation) {
                        $select .= "<option value='circulation'>Circulation</option>";
                    }
                    if (!$data->embedding) {
                        $select .= "<option value='embedding'>Enrobage</option>";
                    }
                    if (!$data->microtomy_spreading) {
                        $select .= "<option value='microtomy_spreading'>Microtomie et Etalement</option>";
                    }
                    if (!$data->staining) {
                        $select .= "<option value='staining'>Coloration</option>";
                    }
                    if (!$data->mounting) {
                        $select .= "<option value='mounting'>Montage</option>";
                    }

                    $select .= "</select>";
                }

                return $select;
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
                    $query->whereDate('created_at','like',$request->get('date'))
                    ->orwhereDate('updated_at','like',$request->get('date'));
                }

            })
            ->rawColumns(['action','code', 'add_by', 'state', 'date_macro', 'date_montage','created'])
            ->make(true);
    }

    // Debut
    public function getTestOrdersforDatatable2(Request $request)
    {


        $data = DB::table('test_orders')
        ->select(
            'test_orders.id as test_order',
            'test_orders.code as code',
            'test_orders.created_at',
            'test_orders.is_urgent',
            'reports.status as report_status',
            'test_pathology_macros.id as test_pathology_macro_id'
        )
        ->join('reports', 'test_orders.id', '=', 'reports.test_order_id')
        ->leftJoin('test_pathology_macros', 'reports.id', '=', 'test_pathology_macros.id_test_pathology_order')
        ->where(function ($query) {
            $query->where('test_orders.is_urgent', 1)
                ->where('reports.status', 0)
                ->whereNotExists(function ($subquery) {
                    $subquery->select(DB::raw(1))
                            ->from('test_pathology_macros')
                            ->whereRaw('id_test_pathology_order = test_orders.id');
                });
        })
        ->orWhere(function ($query) {
            $query->where('reports.status', 0)
                ->whereNotExists(function ($subquery) {
                    $subquery->select(DB::raw(1))
                            ->from('test_pathology_macros')
                            ->whereRaw('id_test_pathology_order = test_orders.id');
                })
                ->whereRaw('DATE_ADD(test_orders.created_at, INTERVAL 10 DAY) <= DATE(NOW() + INTERVAL 1 DAY)');
        })
        ->whereYear('test_orders.created_at', '!=', 2022)
        ->orderByDesc('test_orders.is_urgent') // Ajout de cette ligne pour trier par ordre décroissant selon is_urgent
        ->orderBy('test_orders.created_at')
        ->get();



                $employees = $this->employees->all();
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

            ->addColumn('created', function ($data) {
                return $data->test_order;
            })
            ->addColumn('dateLim', function ($data) {
                $formattedDate = Carbon::parse($data->created_at)->format('Y-m-d');
                // Ajouter 10 jours
                $newDate = Carbon::parse($formattedDate)->addDays(9);
                $newDate = Carbon::parse($newDate)->format('Y-m-d');
                return $newDate;
            })
            ->addColumn('date', function ($data) {
                return dateFormat($data->created_at);
            })
            ->addColumn('code', function ($data) {
                return $data->code;
            })
            ->addColumn('state', function ($data) use ($employees) {
                $escapedCode = htmlspecialchars($data->code, ENT_QUOTES, 'UTF-8');
                $select = "
                    <select name='id_employee' id='{$data->test_order}' class='form-select select2' required data-toggle='select2' onchange='addMacro(".$data->test_order.",\"".$escapedCode."\")'>
                        <option value=''>Tous les laborantins</option>";

                foreach ($employees as $employee) {
                    $select .= "<option value='{$employee->id}'>{$employee->fullname()}</option>";
                }

                $select .= "
                    </select>";

                return $select;
            })
            ->filter(function ($query) use ($request,$data) {

            })
            ->rawColumns(['action','code', 'date', 'state','created','dateLim'])
            ->make(true);
    }
    public function getTestOrdersforDatatable3(Request $request)
    {


        $data =  DB::table('test_orders')
        ->select(
            'test_orders.id AS test_order_id',
            'test_orders.created_at',
            'test_orders.is_urgent',
            'reports.status AS report_status',
            'test_pathology_macros.id AS test_pathology_macro_id'
        )
        ->join('reports', 'test_orders.id', '=', 'reports.test_order_id')
        ->leftJoin('test_pathology_macros', 'reports.id', '=', 'test_pathology_macros.id_test_pathology_order')
        ->where(function ($query) {
            $query->where('test_orders.is_urgent', 1)
                ->where('reports.status', 0)
                ->whereNotExists(function ($subquery) {
                    $subquery->select(DB::raw(1))
                        ->from('test_pathology_macros')
                        ->whereRaw('id_test_pathology_order = test_orders.id');
                });
        })
        ->orWhere(function ($query) {
            $query->where('reports.status', 0)
                ->whereNotExists(function ($subquery) {
                    $subquery->select(DB::raw(1))
                        ->from('test_pathology_macros')
                        ->whereRaw('id_test_pathology_order = test_orders.id');
                })
                ->where(DB::raw('DATE_ADD(test_orders.created_at, INTERVAL 10 DAY)'), '<=', DB::raw('DATE(NOW() + INTERVAL 1 DAY)'));
        })
        ->whereYear('test_orders.created_at', '!=', 2022)
        ->orderBy('test_orders.created_at');




                $employees = $this->employees->all();
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

            ->addColumn('created', function ($data) {
               $checkbox = "
                <div class='form-check'>
                    <input type='checkbox' class='form-check-input'id='customCheck'>
                    // <input type='checkbox' class='form-check-input'id='customCheck".$data->test_order."'>
                </div>
               ";
               return $checkbox;
            })
            ->addColumn('dateLim', function ($data) {
                $formattedDate = Carbon::parse($data->created_at)->format('d-m-Y');
                // Ajouter 10 jours
                $newDate = Carbon::parse($formattedDate)->addDays(9);
                $newDate = Carbon::parse($newDate)->format('d-m-Y');
                return $newDate;
            })
            ->addColumn('code', function ($data) {
                return $data->code;
            })
            ->addColumn('state', function ($data) use ($employees) {
                $escapedCode = htmlspecialchars($data->code, ENT_QUOTES, 'UTF-8');
                $select = "
                    <select id='laborantin{$data->test_order}' class='form-select select2' required data-toggle='select2' onchange='addMacro(".$data->test_order.",\"".$escapedCode."\")'>";

                foreach ($employees as $employee) {
                    $select .= "<option value='{$employee->id}'>{$employee->fullname()}</option>";
                }

                $select .= "
                    </select>";

                return $select;
            })
            ->filter(function ($query) use ($request,$data) {

            })
            ->rawColumns(['action','code', 'date', 'state','created','dateLim'])
            ->make(true);
    }

    public function countData() {
        $data = DB::table('test_orders')
        ->select(
            'test_orders.id as test_order',
            'test_orders.code as code',
            'test_orders.created_at',
            'test_orders.is_urgent',
            'reports.status as report_status',
            'test_pathology_macros.id as test_pathology_macro_id'
        )
        ->join('reports', 'test_orders.id', '=', 'reports.test_order_id')
        ->leftJoin('test_pathology_macros', 'reports.id', '=', 'test_pathology_macros.id_test_pathology_order')
        ->where(function ($query) {
            $query->where('test_orders.is_urgent', 1)
                ->where('reports.status', 0)
                ->whereNotExists(function ($subquery) {
                    $subquery->select(DB::raw(1))
                            ->from('test_pathology_macros')
                            ->whereRaw('id_test_pathology_order = test_orders.id');
                });
        })
        ->orWhere(function ($query) {
            $query->where('reports.status', 0)
                ->whereNotExists(function ($subquery) {
                    $subquery->select(DB::raw(1))
                            ->from('test_pathology_macros')
                            ->whereRaw('id_test_pathology_order = test_orders.id');
                })
                ->whereRaw('DATE_ADD(test_orders.created_at, INTERVAL 10 DAY) <= DATE(NOW() + INTERVAL 1 DAY)');
        })
        ->whereYear('test_orders.created_at', '!=', 2022)
        ->orderByDesc('test_orders.is_urgent') // Ajout de cette ligne pour trier par ordre décroissant selon is_urgent
        ->orderBy('test_orders.created_at')
        ->count();
        return response()->json($data);
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
    public function store2(Request $request) {
        // dd($request);

            $macro = new test_pathology_macro();
            $macro->id_employee = $request->id_employee;
            $macro->date = Carbon::now();
            $macro->user_id = Auth::user()->id;
            if ($request->id != null) {
                $macro->id_test_pathology_order = $request->id;
            }else {
                $order = $this->testOrder->where('code',$request->code)->first();
                $macro->id_test_pathology_order = $order->id;
            }
            $macro->save();

        return response()->json(200);
    }

    public function update(Request $request) {

        $macro = $this->macro->find($request->id);

        if ($request->state == 'circulation') {
            $macro->circulation = true;
        }else if($request->state == 'embedding') {
            $macro->circulation = true;
            $macro->embedding = true;
        }else if($request->state == 'microtomy_spreading') {
            $macro->circulation = true;
            $macro->embedding = true;
            $macro->microtomy_spreading = true;
        }else if($request->state == 'staining') {
            $macro->circulation = true;
            $macro->embedding = true;
            $macro->microtomy_spreading = true;
            $macro->staining = true;
        }else if($request->state == 'mounting') {
            $macro->circulation = true;
            $macro->embedding = true;
            $macro->microtomy_spreading = true;
            $macro->staining = true;
            $macro->mounting = true;
        }
        $macro->save();

        return response()->json($macro);
    }
    public function destroy($id) {

        $macro = $this->macro->find($id);
        $macro->delete();
        return redirect()->route('macro.index')->with('sucess', "Enregistrement effectué avec succès");
    }
}
