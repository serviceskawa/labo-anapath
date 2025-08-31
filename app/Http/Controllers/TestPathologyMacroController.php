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

        $orders = $this->testOrder->whereHas('type', function ($query) {
            $query->where('slug', 'like', 'cytologie')
                ->orwhere('slug', 'like', 'histologie')
                ->orwhere('slug', 'like', 'biopsie')
                ->orwhere('slug', 'like', 'pièce-opératoire')
                ->where('status', 1) // Statut différent de 0
                ->whereNull('deleted_at'); // deleted_at doit être NULL;
        })->get();

        $employees = $this->employees->all();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        $results = DB::table('test_orders')
            ->select(
                'test_orders.id as test_order',
                'test_orders.code as code',
                'test_orders.created_at',
                'test_orders.is_urgent',
                'test_orders.type_order_id as type_order_id',
                'reports.status as report_status',
                'test_pathology_macros.id as test_pathology_macro_id'
            )
            ->join('reports', 'test_orders.id', '=', 'reports.test_order_id')
            ->leftJoin('test_pathology_macros', 'reports.id', '=', 'test_pathology_macros.id_test_pathology_order')
            ->where(function ($query) {
                $query->where('test_orders.type_order_id', '=', 1)
                    ->orwhere('test_orders.type_order_id', '=', 4)
                    ->orwhere('test_orders.type_order_id', '=', 5)
                    ->orwhere('test_orders.type_order_id', '=', 6)
                ;
            })
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

        $type_orders = TypeOrder::latest()->get();
        return view('macro.index', array_merge(compact('type_orders', 'orders', 'employees', 'results')));
    }

    public function searchPathologyOrders(Request $request)
    {
        $search = $request->get('search', '');
        $limit = $request->get('limit', 20);

        $orders = TestOrder::select('id', 'code')
            ->whereHas('type', function ($query) {
                $query->where('status', 1)
                    ->whereNull('deleted_at')
                    ->where(function ($q) {
                        $q->where('slug', 'like', '%cytologie%')
                            ->orWhere('slug', 'like', '%histologie%')
                            ->orWhere('slug', 'like', '%biopsie%')
                            ->orWhere('slug', 'like', '%pièce-opératoire%');
                    });
            })
            ->when($search, function ($query, $search) {
                $query->where('code', 'LIKE', "%{$search}%");
            })
            ->orderBy('code')
            ->paginate($limit);

        return response()->json([
            'data' => $orders->items(),
            'has_more' => $orders->hasMorePages()
        ]);
    }

    public function index_immuno()
    {
        // if (!getOnlineUser()->can('view-test-orders')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        // $orders = $this->testOrder->whereHas('type', function ($query) {
        //     $query->where('slug', 'immuno-interne')
        //         ->orwhere('slug', 'immuno-exterme')->where('status', 1) // Statut différent de 0
        //         ->whereNull('deleted_at'); // deleted_at doit être NULL;
        // })->get();
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
                $query->where(function ($subquery) {
                    $subquery->where('test_orders.is_urgent', 1)
                        ->where('reports.status', 0)
                        ->whereNotExists(function ($subquery) {
                            $subquery->select(DB::raw(1))
                                ->from('test_pathology_macros')
                                ->whereRaw('id_test_pathology_order = test_orders.id');
                        });
                })
                    ->orWhere(function ($subquery) {
                        $subquery->where('reports.status', 0)
                            ->whereNotExists(function ($subquery) {
                                $subquery->select(DB::raw(1))
                                    ->from('test_pathology_macros')
                                    ->whereRaw('id_test_pathology_order = test_orders.id');
                            })
                            ->whereRaw('DATE_ADD(test_orders.created_at, INTERVAL 10 DAY) <= DATE(NOW() + INTERVAL 1 DAY)');
                    });
            })
            ->whereYear('test_orders.created_at', '!=', 2022)
            ->orderBy('test_orders.created_at')
            ->get();

        return view('macro.index_immuno', array_merge(compact('employees', 'results')));
    }

    public function searchMacro(Request $request)
    {
        $search = $request->get('search', '');
        $limit = $request->get('limit', 20);

        // $orders = TestOrder::select('id', 'code')
        //     ->whereDoesntHave('macros') // Remplace !isMacro($order->id)
        //     ->when($search, function ($query, $search) {
        //         $query->where('code', 'LIKE', "%{$search}%");
        //     })
        //     ->orderBy('code')
        //     ->paginate($limit);

        $orders = $this->testOrder->whereHas('type', function ($query) {
            $query->where('slug', 'immuno-interne')
                ->orwhere('slug', 'immuno-exterme')->where('status', 1) // Statut différent de 0
                ->whereNull('deleted_at'); // deleted_at doit être NULL;
        })->orderBy('code')->paginate($limit);

         // Filtrer côté serveur si isMacro() nécessite une logique complexe
    $filteredOrders = $orders->getCollection()->filter(function($order) {
        return !isMacro($order->id);
    })->values();

        return response()->json([
            'data' => $orders->items(),
            'has_more' => $orders->hasMorePages()
        ]);
    }

    // Debut
    public function getTestOrdersforDatatable(Request $request)
    {
        $data = $this->macro->with(['order', 'employee', 'user', 'testOrder'])
            ->whereHas('order', function ($query) {
                $query->whereHas('type', function ($query) {
                    $query->where('slug', 'like', 'cytologie')
                        ->orwhere('slug', 'like', 'histologie')
                        ->orwhere('slug', 'like', 'biopsie')
                        ->orwhere('slug', 'like', 'pièce-opératoire')
                        ->where('status', 1) // Statut différent de 0
                        ->whereNull('deleted_at'); // deleted_at doit être NULL;
                });
            })
            ->orderBy('created_at', 'desc');

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
                if ($data->is_urgent == 1) {
                    if (!empty($data->report)) {
                        if ($data->report->is_deliver == 1) {
                            return 'table-success';
                        } else {
                            if ($data->report->status == 1) {
                                return 'table-warning';
                            }
                        }
                    }
                    return 'table-danger urgent';
                } elseif (!empty($data->report)) {
                    if ($data->report->is_deliver == 1) {
                        return 'table-success';
                    } else {
                        if ($data->report->status == 1) {
                            return 'table-warning';
                        }
                    }
                } else {
                    return '';
                }
            })

            ->addColumn('created', function ($data) {
                $checkbox = "
                 <div class='form-check'>
                     <input type='checkbox' class='form-check-input'id='custom" . $data->test_order . "'>
                     // <input type='checkbox' class='form-check-input'id='custom" . $data->test_order . "'>
                 </div>
                ";
                return $checkbox;
            })

            ->addColumn('action', function ($data) {
                $btnDelete = ' <button type="button" onclick="deleteModal(' . $data->id . ')" class="btn btn-danger" title="Supprimer"><i class="mdi mdi-trash-can-outline"></i> </button>';

                return !isAffecte($data->order->id) ? $btnDelete : '';
            })



            ->addColumn('code', function ($data) {
                $reponse = $data->order->test_affiliate ? "/ " . $data->order->test_affiliate : "";
                return $data->order->code . " " . $reponse;
            })




            // ->addColumn('code', function ($data) {
            //     return $data->order->code;
            // })
            ->addColumn('add_by', function ($data) {
                return $data->employee->fullname();
            })
            ->addColumn('date_macro', function ($data) {
                return dateFormat($data->date);
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
                        <select name='id_test_pathology_order" . $data->id . "' onchange='changeState(" . $data->id . ",\"" . $escapedCode . "\")' id='id_test_pathology_order" . $data->id . "' class='form-select select2' data-toggle='select2'>
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

            ->filter(function ($query) use ($request) {

                if (!empty($request->get('id_test_pathology_order'))) {
                    $query->where('id_test_pathology_order', $request->get('id_test_pathology_order'));
                }
                if (!empty($request->get('id_employee'))) {
                    $query->where('id_employee', $request->get('id_employee'));
                }

                if (!empty($request->get('contenu'))) {
                    $query->whereHas('order', function ($query) use ($request) {
                        $query->where('code', 'like', '%' . $request->get('contenu') . '%');
                    });
                }




                if (!empty($request->get('dateBegin2'))) {
                    $query->whereHas('testOrder', function ($queryModel) use ($request) {

                        $newDate = Carbon::createFromFormat('Y-m-d', $request->get('dateBegin2'));
                        $queryModel->whereDate('created_at', '>=', $newDate);
                    });
                }


                if (!empty($request->get('date'))) {
                    //dd($request);
                    $query->whereDate('created_at', 'like', $request->get('date'))
                        ->orwhereDate('updated_at', 'like', $request->get('date'));
                }
            })
            ->rawColumns(['action', 'code', 'add_by', 'state', 'date_macro', 'date_montage', 'created'])
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
            ->where(function ($query) {
                $query->where('test_orders.status', 1)
                    ->where('type_orders.slug', 'like', 'histologie')
                    ->orwhere('type_orders.slug', 'like', 'cytologie');
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
                if ($data->is_urgent == 1) {
                    if (!empty($data->report)) {
                        if ($data->report->is_deliver == 1) {
                            return 'table-success';
                        } else {
                            if ($data->report->status == 1) {
                                return 'table-warning';
                            }
                        }
                    }
                    return 'table-danger urgent';
                } elseif (!empty($data->report)) {
                    if ($data->report->is_deliver == 1) {
                        return 'table-success';
                    } else {
                        if ($data->report->status == 1) {
                            return 'table-warning';
                        }
                    }
                } else {
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

            // ->addColumn('code', function ($data) {
            //     $reponse = $data->test_affiliate ? "(".$data->test_affiliate.")" : "";
            //     return $data->code . " " . $reponse;
            // })

            // ->addColumn('code', function ($data) {
            //     return $data->code;
            // })

            ->addColumn('code', function ($data) {
                $reponse = $data->test_affiliate ? "/ " . $data->test_affiliate : "";
                return $data->code . " " . $reponse;
            })


            ->addColumn('state', function ($data) use ($employees) {
                $escapedCode = htmlspecialchars($data->code, ENT_QUOTES, 'UTF-8');
                $select = "
                    <select name='id_employee' id='{$data->test_order}' class='form-select select2' required data-toggle='select2' onchange='addMacro(" . $data->test_order . ",\"" . $escapedCode . "\")'>
                        <option value=''>Tous les laborantins</option>";

                foreach ($employees as $employee) {
                    $select .= "<option value='{$employee->id}'>{$employee->fullname()}</option>";
                }

                $select .= "
                    </select>";

                return $select;
            })
            ->filter(function ($query) use ($request, $data) {})
            ->rawColumns(['action', 'code', 'date', 'state', 'created', 'dateLim'])
            ->make(true);
    }

    public function getTestOrdersforDatatable3(Request $request)
    {
        $data = DB::table('test_orders')
            ->select(
                'test_orders.id as test_order',
                'test_orders.code as code',
                'test_orders.created_at',
                'test_orders.type_order_id as type_order_id',
                'test_orders.is_urgent',
                'test_orders.test_affiliate',
                'reports.status as report_status',
                'test_pathology_macros.id as test_pathology_macro_id'
            )
            // Jointure entre la table test-orders et reports
            ->join('reports', 'test_orders.id', '=', 'reports.test_order_id')
            ->join('type_orders', 'test_orders.type_order_id', '=', 'type_orders.id')
            ->leftJoin('test_pathology_macros', 'test_orders.id', '=', 'test_pathology_macros.id_test_pathology_order')
            ->whereNull('test_pathology_macros.id')
            // Filtrage des compte rendu en attente
            ->where('reports.status', 0)
            ->whereYear('reports.created_at', '!=', 2022)
            ->whereYear('reports.created_at', '!=', 2023)

            // Filtrage histologie ou biopsie
            ->where(function ($query) {
                $query
                    ->where('type_order_id', 1)
                    ->orwhere('type_order_id', 5)
                    ->orwhere('type_order_id', 6)
                    ->orwhere('type_order_id', 4);
            })->where('test_orders.is_urgent', 1);


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
                if ($data->is_urgent == 1) {
                    if (!empty($data->report)) {
                        if ($data->report->is_deliver == 1) {
                            return 'table-success';
                        } else {
                            if ($data->report->status == 1) {
                                return 'table-warning';
                            }
                        }
                    }
                    return 'table-danger urgent';
                } elseif (!empty($data->report)) {
                    if ($data->report->is_deliver == 1) {
                        return 'table-success';
                    } else {
                        if ($data->report->status == 1) {
                            return 'table-warning';
                        }
                    }
                } else {
                    return '';
                }
            })

            ->addColumn('created', function ($data) {
                $checkbox = "
                <div class='form-check'>
                    <input type='checkbox' class='form-check-input'id='customCheck'>
                    // <input type='checkbox' class='form-check-input'id='customCheck" . $data->test_order . "'>
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

            // ->addColumn('code', function ($data) {
            //     return $data->code;
            // })

            ->addColumn('code', function ($data) {
                $reponse = $data->test_affiliate ? "/ " . $data->test_affiliate : "";
                return $data->code . " " . $reponse;
            })

            ->addColumn('state', function ($data) use ($employees) {
                $escapedCode = htmlspecialchars($data->code, ENT_QUOTES, 'UTF-8');
                $select = "
                    <select id='laborantin{$data->test_order}' class='form-select select2' required data-toggle='select2' onchange='addMacro(" . $data->test_order . ",\"" . $escapedCode . "\")'>";

                foreach ($employees as $employee) {
                    $select .= "<option value='{$employee->id}'>{$employee->fullname()}</option>";
                }

                $select .= "
                    </select>";

                return $select;
            })

            ->filter(function ($query) use ($request) {
                if ($request->get('typeOrderId')) {
                    $query->where('type_order_id', $request->get('typeOrderId'));
                }
            })

            // ->filter(function ($query) use ($request) {
            //     if  ($request->get('typeOrderId')) {
            //         $query->where(function($query) use ($request){
            //             $query->where('type_order_id',$request->get('typeOrderId'));
            //         });
            //     }
            // })


            ->rawColumns(['action', 'code', 'date', 'state', 'created', 'dateLim'])
            ->make(true);
    }

    // Histoligie Piece Operatoire
    public function getTestOrdersforDatatableHistologie(Request $request)
    {
        $data = DB::table('test_orders')
            ->select(
                'test_orders.id as test_order',
                'test_orders.code as code',
                'test_orders.created_at',
                'test_orders.type_order_id as type_order_id',
                'test_orders.is_urgent',
                'test_orders.test_affiliate',
                'reports.status as report_status',
                'test_pathology_macros.id as test_pathology_macro_id'
            )
            // Jointure entre la table test-orders et reports
            ->join('reports', 'test_orders.id', '=', 'reports.test_order_id')
            ->join('type_orders', 'test_orders.type_order_id', '=', 'type_orders.id')
            ->leftJoin('test_pathology_macros', 'test_orders.id', '=', 'test_pathology_macros.id_test_pathology_order')
            ->whereNull('test_pathology_macros.id')
            // Filtrage des compte rendu en attente
            ->where('reports.status', 0)
            ->whereYear('reports.created_at', '!=', 2022)
            ->whereYear('reports.created_at', '!=', 2023)

            // Filtrage histologie ou biopsie
            ->where(function ($query) {
                $query
                    ->where('type_order_id', 1)
                    ->orwhere('type_order_id', 5);
            });



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
                if ($data->is_urgent == 1) {
                    if (!empty($data->report)) {
                        if ($data->report->is_deliver == 1) {
                            return 'table-success';
                        } else {
                            if ($data->report->status == 1) {
                                return 'table-warning';
                            }
                        }
                    }
                    return 'table-danger urgent';
                } elseif (!empty($data->report)) {
                    if ($data->report->is_deliver == 1) {
                        return 'table-success';
                    } else {
                        if ($data->report->status == 1) {
                            return 'table-warning';
                        }
                    }
                } else {
                    return '';
                }
            })

            ->addColumn('created', function ($data) {
                $checkbox = "
                <div class='form-check'>
                    <input type='checkbox' class='form-check-input'id='customCheck'>
                    // <input type='checkbox' class='form-check-input'id='customCheck" . $data->test_order . "'>
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

            // ->addColumn('code', function ($data) {
            //     return $data->code;
            // })

            ->addColumn('code', function ($data) {
                $reponse = $data->test_affiliate ? "/ " . $data->test_affiliate : "";
                return $data->code . " " . $reponse;
            })

            ->addColumn('state', function ($data) use ($employees) {
                $escapedCode = htmlspecialchars($data->code, ENT_QUOTES, 'UTF-8');
                $select = "
                    <select id='laborantin{$data->test_order}' class='form-select select2' required data-toggle='select2' onchange='addMacro(" . $data->test_order . ",\"" . $escapedCode . "\")'>";

                foreach ($employees as $employee) {
                    $select .= "<option value='{$employee->id}'>{$employee->fullname()}</option>";
                }

                $select .= "
                    </select>";

                return $select;
            })

            ->filter(function ($query) use ($request) {
                if ($request->get('typeOrderId')) {
                    $query->where('type_order_id', $request->get('typeOrderId'));
                }
            })

            ->rawColumns(['action', 'code', 'date', 'state', 'created', 'dateLim'])
            ->make(true);
    }

    // Histoligie Piece Operatoire
    public function getTestOrdersforDatatablePieceOperatoire(Request $request)
    {
        $data = DB::table('test_orders')
            ->select(
                'test_orders.id as test_order',
                'test_orders.code as code',
                'test_orders.created_at',
                'test_orders.type_order_id as type_order_id',
                'test_orders.is_urgent',
                'test_orders.test_affiliate',
                'reports.status as report_status',
                'test_pathology_macros.id as test_pathology_macro_id'
            )
            // Jointure entre la table test-orders et reports
            ->join('reports', 'test_orders.id', '=', 'reports.test_order_id')
            ->join('type_orders', 'test_orders.type_order_id', '=', 'type_orders.id')
            ->leftJoin('test_pathology_macros', 'test_orders.id', '=', 'test_pathology_macros.id_test_pathology_order')
            ->whereNull('test_pathology_macros.id')
            // Filtrage des compte rendu en attente
            ->where('reports.status', 0)
            ->whereYear('reports.created_at', '!=', 2022)
            ->whereYear('reports.created_at', '!=', 2023)

            // Filtrage histologie ou biopsie
            ->where(function ($query) {
                $query
                    ->where('type_order_id', 6);
            });

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
                if ($data->is_urgent == 1) {
                    if (!empty($data->report)) {
                        if ($data->report->is_deliver == 1) {
                            return 'table-success';
                        } else {
                            if ($data->report->status == 1) {
                                return 'table-warning';
                            }
                        }
                    }
                    return 'table-danger urgent';
                } elseif (!empty($data->report)) {
                    if ($data->report->is_deliver == 1) {
                        return 'table-success';
                    } else {
                        if ($data->report->status == 1) {
                            return 'table-warning';
                        }
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('created', function ($data) {
                $checkbox = "
                <div class='form-check'>
                    <input type='checkbox' class='form-check-input'id='customCheck'>
                    // <input type='checkbox' class='form-check-input'id='customCheck" . $data->test_order . "'>
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

            // ->addColumn('code', function ($data) {
            //     return $data->code;
            // })

            ->addColumn('code', function ($data) {
                $reponse = $data->test_affiliate ? "/ " . $data->test_affiliate : "";
                return $data->code . " " . $reponse;
            })


            ->addColumn('state', function ($data) use ($employees) {
                $escapedCode = htmlspecialchars($data->code, ENT_QUOTES, 'UTF-8');
                $select = "
                    <select id='laborantin{$data->test_order}' class='form-select select2' required data-toggle='select2' onchange='addMacro(" . $data->test_order . ",\"" . $escapedCode . "\")'>";

                foreach ($employees as $employee) {
                    $select .= "<option value='{$employee->id}'>{$employee->fullname()}</option>";
                }

                $select .= "
                    </select>";

                return $select;
            })
            ->filter(function ($query) use ($request) {
                if ($request->get('typeOrderId')) {
                    $query->where('type_order_id', $request->get('typeOrderId'));
                }
            })
            ->rawColumns(['action', 'code', 'date', 'state', 'created', 'dateLim'])
            ->make(true);
    }

    // Histoligie Cytologie
    public function getTestOrdersforDatatableCytologie(Request $request)
    {
        $data = DB::table('test_orders')
            ->select(
                'test_orders.id as test_order',
                'test_orders.code as code',
                'test_orders.created_at',
                'test_orders.type_order_id as type_order_id',
                'test_orders.is_urgent',
                'test_orders.test_affiliate',
                'reports.status as report_status',
                'test_pathology_macros.id as test_pathology_macro_id'
            )
            // Jointure entre la table test-orders et reports
            ->join('reports', 'test_orders.id', '=', 'reports.test_order_id')
            ->join('type_orders', 'test_orders.type_order_id', '=', 'type_orders.id')
            ->leftJoin('test_pathology_macros', 'test_orders.id', '=', 'test_pathology_macros.id_test_pathology_order')
            ->whereNull('test_pathology_macros.id')
            // Filtrage des compte rendu en attente
            ->where('reports.status', 0)
            ->whereYear('reports.created_at', '!=', 2022)
            ->whereYear('reports.created_at', '!=', 2023)

            // Filtrage histologie ou biopsie
            ->where(function ($query) {
                $query
                    ->where('type_order_id', 4);
            });





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
                if ($data->is_urgent == 1) {
                    if (!empty($data->report)) {
                        if ($data->report->is_deliver == 1) {
                            return 'table-success';
                        } else {
                            if ($data->report->status == 1) {
                                return 'table-warning';
                            }
                        }
                    }

                    return 'table-danger urgent';
                } elseif (!empty($data->report)) {
                    if ($data->report->is_deliver == 1) {
                        return 'table-success';
                    } else {
                        if ($data->report->status == 1) {
                            return 'table-warning';
                        }
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('created', function ($data) {
                $checkbox = "
                <div class='form-check'>
                    <input type='checkbox' class='form-check-input'id='customCheck'>
                    // <input type='checkbox' class='form-check-input'id='customCheck" . $data->test_order . "'>
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

            // ->addColumn('code', function ($data) {
            //     return $data->code;
            // })

            ->addColumn('code', function ($data) {
                $reponse = $data->test_affiliate ? "/ " . $data->test_affiliate : "";
                return $data->code . " " . $reponse;
            })

            ->addColumn('state', function ($data) use ($employees) {
                $escapedCode = htmlspecialchars($data->code, ENT_QUOTES, 'UTF-8');
                $select = "
                    <select id='laborantin{$data->test_order}' class='form-select select2' required data-toggle='select2' onchange='addMacro(" . $data->test_order . ",\"" . $escapedCode . "\")'>";

                foreach ($employees as $employee) {
                    $select .= "<option value='{$employee->id}'>{$employee->fullname()}</option>";
                }

                $select .= "
                    </select>";

                return $select;
            })
            ->filter(function ($query) use ($request) {
                if ($request->get('typeOrderId')) {
                    $query->where('type_order_id', $request->get('typeOrderId'));
                }
            })
            ->rawColumns(['action', 'code', 'date', 'state', 'created', 'dateLim'])
            ->make(true);
    }

    public function getTestOrdersforDatatable_immuno(Request $request)
    {
        $data = $this->macro->with(['order', 'employee', 'user'])
            ->whereHas('order', function ($query) {
                $query->whereHas('type', function ($query) {
                    $query->where('slug', 'immuno-interne')
                        ->orwhere('slug', 'immuno-exterme')->where('status', 1)
                        ->whereNull('deleted_at');
                });
            })
            ->orderBy('created_at', 'desc');

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
                if ($data->is_urgent == 1) {
                    if (!empty($data->report)) {
                        if ($data->report->is_deliver == 1) {
                            return 'table-success';
                        } else {
                            if ($data->report->status == 1) {
                                return 'table-warning';
                            }
                        }
                    }
                    return 'table-danger urgent';
                } elseif (!empty($data->report)) {
                    if ($data->report->is_deliver == 1) {
                        return 'table-success';
                    } else {
                        if ($data->report->status == 1) {
                            return 'table-warning';
                        }
                    }
                } else {
                    return '';
                }
            })

            ->addColumn('created', function ($data) {
                $checkbox = "
                 <div class='form-check'>
                     <input type='checkbox' class='form-check-input'id='custom" . $data->test_order . "'>
                     // <input type='checkbox' class='form-check-input'id='custom" . $data->test_order . "'>
                 </div>
                ";
                return $checkbox;
            })

            ->addColumn('action', function ($data) {
                $btnDelete = ' <button type="button" onclick="deleteModal(' . $data->id . ')" class="btn btn-danger" title="Supprimer"><i class="mdi mdi-trash-can-outline"></i> </button>';

                return !isAffecte($data->order->id) ? $btnDelete : '';
            })
            // ->addColumn('code', function ($data) {
            //     return $data->order->code;
            // })
            ->addColumn('code', function ($data) {
                $reponse = $data->order->test_affiliate ? "/ " . $data->order->test_affiliate : "";
                return $data->order->code . " " . $reponse;
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
                        <select name='id_test_pathology_order" . $data->id . "' onchange='changeState(" . $data->id . ",\"" . $escapedCode . "\")' id='id_test_pathology_order" . $data->id . "' class='form-select select2' data-toggle='select2'>
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

            ->filter(function ($query) use ($request, $data) {

                if (!empty($request->get('id_test_pathology_order'))) {
                    $query->where('id_test_pathology_order', $request->get('id_test_pathology_order'));
                }
                if (!empty($request->get('id_employee'))) {
                    $query->where('id_employee', $request->get('id_employee'));
                }

                if (!empty($request->get('date'))) {
                    //dd($request);
                    $query->whereDate('created_at', 'like', $request->get('date'))
                        ->orwhereDate('updated_at', 'like', $request->get('date'));
                }
            })
            ->rawColumns(['action', 'code', 'add_by', 'state', 'date_macro', 'date_montage', 'created'])
            ->make(true);
    }

    // Debut
    public function getTestOrdersforDatatable2_immuno(Request $request)
    {
        $data = DB::table('test_orders')
            ->select(
                'test_orders.id as test_order',
                'test_orders.code as code',
                'test_orders.created_at',
                'test_orders.is_urgent',
                'test_orders.test_affiliate',
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
            ->whereHas('type', function ($query) {
                $query->where('slug', 'immuno-interne')
                    ->orwhere('slug', 'immuno-exterme');
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
                if ($data->is_urgent == 1) {
                    if (!empty($data->report)) {
                        if ($data->report->is_deliver == 1) {
                            return 'table-success';
                        } else {
                            if ($data->report->status == 1) {
                                return 'table-warning';
                            }
                        }
                    }
                    return 'table-danger urgent';
                } elseif (!empty($data->report)) {
                    if ($data->report->is_deliver == 1) {
                        return 'table-success';
                    } else {
                        if ($data->report->status == 1) {
                            return 'table-warning';
                        }
                    }
                } else {
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
            // ->addColumn('code', function ($data) {
            //     return $data->code;
            // })
            ->addColumn('code', function ($data) {
                $reponse = $data->test_affiliate ? "/ " . $data->test_affiliate : "";
                return $data->code . " " . $reponse;
            })
            ->addColumn('state', function ($data) use ($employees) {
                $escapedCode = htmlspecialchars($data->code, ENT_QUOTES, 'UTF-8');
                $select = "
                    <select name='id_employee' id='{$data->test_order}' class='form-select select2' required data-toggle='select2' onchange='addMacro(" . $data->test_order . ",\"" . $escapedCode . "\")'>
                        <option value=''>Tous les laborantins</option>";

                foreach ($employees as $employee) {
                    $select .= "<option value='{$employee->id}'>{$employee->fullname()}</option>";
                }

                $select .= "
                    </select>";

                return $select;
            })
            ->filter(function ($query) use ($request, $data) {})
            ->rawColumns(['action', 'code', 'date', 'state', 'created', 'dateLim'])
            ->make(true);
    }

    public function getTestOrdersforDatatable3_immuno(Request $request)
    {
        $data = DB::table('test_orders')
            ->select(
                'test_orders.id as test_order',
                'test_orders.code as code',
                'test_orders.created_at',
                'test_orders.is_urgent',
                'test_orders.test_affiliate',
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
            ->whereHas('type', function ($query) {
                $query->where('slug', 'immuno-interne')
                    ->orwhere('slug', 'immuno-exterme');
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
            // ->orderByDesc('test_orders.is_urgent') // Ajout de cette ligne pour trier par ordre décroissant selon is_urgent
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
                if ($data->is_urgent == 1) {
                    if (!empty($data->report)) {
                        if ($data->report->is_deliver == 1) {
                            return 'table-success';
                        } else {
                            if ($data->report->status == 1) {
                                return 'table-warning';
                            }
                        }
                    }
                    return 'table-danger urgent';
                } elseif (!empty($data->report)) {
                    if ($data->report->is_deliver == 1) {
                        return 'table-success';
                    } else {
                        if ($data->report->status == 1) {
                            return 'table-warning';
                        }
                    }
                } else {
                    return '';
                }
            })

            ->addColumn('created', function ($data) {
                $checkbox = "
                <div class='form-check'>
                    <input type='checkbox' class='form-check-input'id='customCheck'>
                    // <input type='checkbox' class='form-check-input'id='customCheck" . $data->test_order_id . "'>
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
            // ->addColumn('code', function ($data) {
            //     return $data->code;
            // })
            ->addColumn('code', function ($data) {
                $reponse = $data->test_affiliate ? "/ " . $data->test_affiliate : "";
                return $data->code . " " . $reponse;
            })
            ->addColumn('state', function ($data) use ($employees) {
                $escapedCode = htmlspecialchars($data->code, ENT_QUOTES, 'UTF-8');
                $select = "

                    <select id='laborantin{$data->test_order}' class='form-select select2' required data-toggle='select2' onchange='addMacro(" . $data->test_order . ",\"" . $escapedCode . "\")'>";


                foreach ($employees as $employee) {
                    $select .= "<option value='{$employee->id}'>{$employee->fullname()}</option>";
                }

                $select .= "
                    </select>";

                return $select;
            })
            ->filter(function ($query) use ($request, $data) {})
            ->rawColumns(['action', 'code', 'date', 'state', 'created', 'dateLim'])
            ->make(true);
    }

    public function countData()
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
            ->count();
        return response()->json($data);
    }

    public function create()
    {
        $orders = $this->testOrder->whereHas('type', function ($query) {
            $query->where('slug', 'cytologie')
                ->orwhere('slug', 'histologie')
                ->orwhere('slug', 'biopsie')
                ->orwhere('slug', 'pièce-opératoire')
                ->where('status', 1) // Statut différent de 0
                ->whereNull('deleted_at'); // deleted_at doit être NULL;
        })->get();
        $employees = $this->employees->all();
        return view('macro.create', array_merge(compact('orders', 'employees')));
    }

    public function create_immuno()
    {
        $orders = $this->testOrder->whereHas('type', function ($query) {
            $query->where('slug', 'immuno-interne')
                ->orwhere('slug', 'immuno-exterme')
                ->where('status', 1) // Statut différent de 0
                ->whereNull('deleted_at'); // deleted_at doit être NULL;
        })->get();
        $employees = $this->employees->all();
        return view('macro.create_immuno', array_merge(compact('orders', 'employees')));
    }

    public function store(Request $request)
    {
        $orders = $request->orders;

        foreach ($orders as $key => $order) {
            $macro = new test_pathology_macro();
            $macro->id_employee = $request->id_employee;
            $macro->date = $request->date;
            $macro->id_test_pathology_order = $order;
            $macro->user_id = Auth::user()->id;
            $macro->save();
        }

        return redirect()->route('macro.index')->with('sucess', "Enregistrement effectué avec succès");
    }
    public function store2(Request $request)
    {
        $macro = new test_pathology_macro();
        $macro->id_employee = $request->id_employee;
        $macro->date = Carbon::now();
        $macro->user_id = Auth::user()->id;
        if ($request->id != null) {
            $macro->id_test_pathology_order = $request->id;
        } else {
            $order = $this->testOrder->where('code', $request->code)->first();
            $macro->id_test_pathology_order = $order->id;
        }
        $macro->save();

        return response()->json(200);
    }

    public function update(Request $request)
    {

        $macro = $this->macro->find($request->id);

        if ($request->state == 'circulation') {
            $macro->circulation = true;
        } else if ($request->state == 'embedding') {
            $macro->circulation = true;
            $macro->embedding = true;
        } else if ($request->state == 'microtomy_spreading') {
            $macro->circulation = true;
            $macro->embedding = true;
            $macro->microtomy_spreading = true;
        } else if ($request->state == 'staining') {
            $macro->circulation = true;
            $macro->embedding = true;
            $macro->microtomy_spreading = true;
            $macro->staining = true;
        } else if ($request->state == 'mounting') {
            $macro->circulation = true;
            $macro->embedding = true;
            $macro->microtomy_spreading = true;
            $macro->staining = true;
            $macro->mounting = true;
        }
        $macro->save();

        return response()->json($macro);
    }

    public function destroy($id)
    {
        $macro = $this->macro->find($id);
        $macro->delete();
        return redirect()->route('macro.index')->with('sucess', "Enregistrement effectué avec succès");
    }
}
