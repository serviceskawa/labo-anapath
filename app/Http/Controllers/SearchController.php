<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TestOrderRequest;
use App\Models\AppelByReport;
use App\Models\AppelTestOder;
use App\Models\Cashbox;
use Illuminate\Support\Str;

use App\Models\Setting;
use App\Models\Hospital;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Test;
use App\Models\Doctor;
use App\Models\Contrat;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\TestOrder;
use App\Models\TypeOrder;
use App\Models\InvoiceDetail;
use App\Models\DetailTestOrder;
use App\Models\LogReport;
use App\Models\TestOrderAssignment;
use App\Models\TestOrderAssignmentDetail;
use App\Services\ReportFilterService;
use App\Exports\ReportsGlobalSearchExport;
use Maatwebsite\Excel\Facades\Excel;


class SearchController extends Controller
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
    protected $testOrderAssignment;
    protected $testOrderAssignmentDetail;

    public function __construct(
        Test $test,
        TestOrder $testOrder,
        TestOrderAssignment $testOrderAssignment,
        TestOrderAssignmentDetail $testOrderAssignmentDetail,
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
        $this->testOrderAssignmentDetail = $testOrderAssignmentDetail;
        $this->testOrderAssignment = $testOrderAssignment;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::latest()->get();
        $doctors = Doctor::latest()->get();
        $test_orders = TestOrder::latest()->get();
        $hopitals = Hospital::latest()->get();
        $contrats = Contrat::latest()->get();
        $types_orders = TypeOrder::latest()->get();

        return view('searchs.index', compact(['patients', 'doctors', 'hopitals', 'contrats', 'types_orders', 'test_orders']));
    }

    // public function getDataforDatatableSearchGlobal(Request $request)
    // {
    //     $query = ReportFilterService::getFilteredQuery($request);

    //     return DataTables::of($query)
    //         ->addIndexColumn()
    //         ->addColumn('type_examen', fn($data) => $data?->order?->type?->title)
    //         ->addColumn('contract_name', fn($data) => $data?->order?->contrat?->name)
    //         ->addColumn('patient_name', fn($data) => $data?->order?->patient?->firstname . ' ' . $data?->order?->patient?->lastname)
    //         ->addColumn('doctor_name', fn($data) => $data?->order?->doctorExamen?->name)
    //         ->addColumn('hospital_name', fn($data) => $data?->order?->hospital?->name)
    //         ->addColumn('reference_hospital_name', fn($data) => $data?->order?->reference_hospital)
    //         ->addColumn('dateCreation', fn($data) => \Carbon\Carbon::parse($data->created_at)->format('d/m/Y'))
    //         ->addColumn('codeReport', fn($data) => $data?->code)
    //         ->addColumn('codeExamen', fn($data) => $data?->order?->code)
    //         ->addColumn('urgenceStatusTestOrder', fn($data) => $data->status == 1 ? 'Oui' : 'Non')
    //         ->make(true);
    // }

     public function export(Request $request)
    {
        return Excel::download(new ReportsGlobalSearchExport($request), 'rapports.xlsx');
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

    public function getDataforDatatableSearchGlobal(Request $request)
    {
        $data = Report::with('order')->latest();
                // $data = $this->report->with('order')->latest();
        return DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('type_examen', function ($data) {
                return $data?->order?->type?->title;
            })

            ->addColumn('contract_name', function ($data) {
                return $data?->order?->contrat?->name;
            })

            ->addColumn('patient_name', function ($data) {
                return $data?->order?->patient?->firstname . ' ' . $data?->order?->patient?->lastname;
            })

            ->addColumn('doctor_name', function ($data) {
                return $data?->order?->doctorExamen?->name . ' ' . $data?->order?->patient?->lastname;
            })

            ->addColumn('hospital_name', function ($data) {
                return $data?->order?->hospital?->name;
            })

            ->addColumn('reference_hospital_name', function ($data) {
                return $data?->order?->reference_hopital;
            })

            ->addColumn('dateCreation', function ($data) {
                return \Carbon\Carbon::parse($data->created_at)->format('d/m/Y');
            })

            ->addColumn('codeReport', function ($data) {
                return $data?->code;
            })

            ->addColumn('codeExamen', function ($data) {
                return $data?->order?->code;
            })

            ->addColumn('urgenceStatusTestOrder', function ($data) {
                if ($data->status == 1) {
                    return 'Oui';
                } else {
                    return 'Non';
                }
            })

            // ->addColumn('action', function ($data) {
            //     $btnVoir = '<a type="button" href="' . route('report.show', $data->id) . '"class="btn btn-primary"><i class="mdi mdi-eye"></i> </a>';
            //     if ($data->order) {
            //         $btnReport = ' <a type="button" href="' . route('details_test_order.index', $data->order->id) . '" class="btn btn-warning" title="Demande ' . $data->order->code . '"><i class="uil-file-medical"></i> </a>';
            //     } else {
            //         $btnReport = ' ';
            //     }

            //     return $btnVoir . $btnReport;
            // })
            ->filter(function ($query) use ($request) {
                if (!empty($request->get('type_examen_ids'))) {
                    $query
                        ->whereHas('order', function ($query) use ($request) {
                            $query->where('type_order_id', $request->get('type_examen_ids', []));
                        });
                }

                if (!empty($request->get('contrat_ids'))) {
                    $query
                        ->whereHas('order', function ($query) use ($request) {
                            $query->where('contrat_id', $request->get('contrat_ids', []));
                        });
                }

                if (!empty($request->get('patient_ids'))) {
                    $query
                        ->whereHas('order', function ($query) use ($request) {
                            $query->where('patient_id', $request->get('patient_ids', []));
                        });
                }

                if (!empty($request->get('doctor_ids'))) {
                    $query
                        ->whereHas('order', function ($query) use ($request) {
                            $query->where('doctor_id', $request->get('doctor_ids', []));
                        });
                }

                if (!empty($request->get('hospital_ids'))) {
                    $query
                        ->whereHas('order', function ($query) use ($request) {
                            $query->where('hospital_id', $request->get('hospital_ids', []));
                        });
                }

                if (!empty($request->get('reference_hopital'))) {
                    $query
                        ->whereHas('order', function ($query) use ($request) {
                            $query->where('reference_hopital', 'like', '%' . $request->get('reference_hopital') . '%');
                        });
                }

                if (!empty($request->get('status_urgence_test_order_id'))) {
                    if ($request->get('status_urgence_test_order_id') == 1) {
                        $query->where('status', 1);
                    } else {
                        $query->where('status', 0);
                    }
                }

                if (!empty($request->get('content'))) {
                    $query
                        ->where('code', 'like', '%' . $request->get('content') . '%')
                        ->orwhere('description', 'like', '%' . $request->get('content') . '%')
                        ->orwhere('description_supplementaire', 'like', '%' . $request->get('content') . '%')
                        ->orwhere('title', 'like', '%' . $request->get('content') . '%')
                        // ->orwhere('delivery_date', 'like', '%' . $request->get('content') . '%')
                        // ->orwhere('signature_date', 'like', '%' . $request->get('content') . '%')
                        // ->orwhere('retriever_date', 'like', '%' . $request->get('content') . '%')

                        ->orwhereHas('order', function ($query) use ($request) {
                            $query->where('code', 'like', '%' . $request->get('content') . '%')
                            // ->orwhere('prelevement_date', 'like', '%' . $request->get('content') . '%')
                            ;
                        })

                        // ->orwhereHas('order', function ($query) use ($request) {
                        //     $query->whereHas('patient', function ($query) use ($request) {
                        //         $query->where('firstname', 'like', '%' . $request->get('content') . '%')
                        //             ->orwhere('code', 'like', '%' . $request->get('content') . '%')
                        //             ->orwhere('lastname', 'like', '%' . $request->get('content') . '%');
                        //     })

                        //     ->orwhereHas('hospital', function ($query) use ($request) {
                        //         $query->where('name', 'like', '%' . $request->get('content') . '%')
                        //         ->orWhere('email', 'like', '%' . $request->get('content') . '%')
                        //         ->orWhere('telephone', 'like', '%' . $request->get('content') . '%')
                        //         ->orWhere('adresse', 'like', '%' . $request->get('content') . '%');
                        //     })

                        //     ->orwhereHas('doctorExamen', function ($query) use ($request) {
                        //         $query->where('name', 'like', '%' . $request->get('content') . '%');
                        //     })

                        //     ->orwhereHas('contrat', function ($query) use ($request) {
                        //         $query->where('name', 'like', '%' . $request->get('content') . '%')
                        //         ->orWhere('type', 'like', '%' . $request->get('content') . '%')
                        //         ->orWhere('description', 'like', '%' . $request->get('content') . '%');
                        //     })
                        //     ;
                        // });
                }

                if (!empty($request->get('dateBegin'))) {
                    $newDate = Carbon::createFromFormat('Y-m-d', $request->get('dateBegin'));
                    $query->whereDate('created_at', '>', $newDate);
                }

                if (!empty($request->get('dateEnd'))) {
                    $newDate = Carbon::createFromFormat('Y-m-d', $request->get('dateEnd'));
                    $query->whereDate('created_at', '<', $newDate);
                }
            })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
