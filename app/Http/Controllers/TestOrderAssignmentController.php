<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\test_pathology_macro;
use App\Models\TestOrder;
use App\Models\TestOrderAssignment;
use App\Models\TestOrderAssignmentDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TestOrderAssignmentController extends Controller
{
    protected $order;
    protected $assignment;
    protected $user;
    protected $details;

    public function __construct(TestOrder $order,TestOrderAssignment $assignment, User $user, TestOrderAssignmentDetail $details)
    {
        $this->order = $order;
        $this->assignment = $assignment;
        $this->user = $user;
        $this->details = $details;
    }

    public function index()
    {
        $assignments = $this->assignment->whereHas('details', function($query) {
            $query->whereHas('order', function($query){
                $query->whereHas('type', function($query){
                    $query->where('slug','like','cytologie')
                            ->orwhere('slug','!=','histologie');
                })->where('status', 1) // Statut différent de 0
                ->whereNull('deleted_at'); // deleted_at doit être NULL
            });
        })->latest()->get();

        $orders = $this->order->all();

        return view('reports.assignment.index',compact('assignments','orders'));
    }

    public function index_immuno()
    {
        $assignments = $this->assignment->whereHas('details', function($query) {
            $query->whereHas('order', function($query){
                $query->whereHas('type', function($query){
                    $query->where('slug','immuno-interne')
                            ->orwhere('slug','immuno-exterme');
                })->where('status', 1) // Statut différent de 0
                ->whereNull('deleted_at'); // deleted_at doit être NULL
            });
        })->latest()->get();
        $orders = $this->order->whereHas('type', function($query){
            $query->where('slug','immuno-interne')
                    ->orwhere('slug','immuno-exterme');
        })->latest()->get();

        return view('reports.assignment.index',compact('assignments','orders'));
    }

    public function getdetail($id)
    {
        $assignment = $this->assignment->find($id);
        $details = $this->details->where('test_order_assignment_id', $assignment->id)->get();


        return response()->json($details);
    }

    public function index_detail($id)
    {
        $assignment = $this->assignment->find($id);
        $testOrders = $this->order
                    ->where('status', 1) // Statut différent de 0
                    ->whereNull('deleted_at') // deleted_at doit être NULL
        ->latest()->get();

        return view('reports.assignment.create',compact('assignment','testOrders'));
    }

    public function index_immuno_detail($id)
    {
        $assignment = $this->assignment->find($id);
        $testOrders = $this->order->whereHas('type', function($query){
                    $query->where('slug','immuno-interne')
                            ->orwhere('slug','immuno-exterme')
                            ->where('status', 1) // Statut différent de 0
                            ->whereNull('deleted_at'); // deleted_at doit être NULL
                })->latest()->get();

        return view('reports.assignment.create',compact('assignment','testOrders'));
    }

    public function store(Request $request)
    {
        // dd($request);
        try {
            $code = generateCodeAssignment();
            $assignment = $this->assignment->create([
                'code' => $code,
                'user_id' => $request->user_id
            ]);
            return redirect()->route('report.assignment.detail.index',$assignment->id);
        } catch (\Throwable $th) {
            return back()->with('error', "Erreur de'enregistrement".$th->getMessage());
        }
    }

    public function update(Request $request)
    {
        $assignment = $this->assignment->find($request->id);
        // dd($request->date);
        try {
            $assignment->update([
                'user_id' => $request->user_id,
                'note' => $request->note_assignment,
                'date' => $request->date
            ]);
            return back()->with('success',"Mise à jour effectuée aves succès");
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('success',"Erreur lors de la mise à jour ". $th->getMessage());
        }
    }

    public function store_detail(Request $request)
    {
        $data = [
            'test_order_assignment_id' => $request->test_order_assignment_id,
            'test_order_id' => $request->test_order_id,
            'note' => $request->note,
            'confirmation' => $request->confirm
        ];
        $order = $this->order->find($request->test_order_id);


        try {
            // isAffecte($data['test_order_id']) ? $data['confirmation'] = false : true;
            if (!isAffecte($data['test_order_id'])) {
                $detail = TestOrderAssignmentDetail::where('test_order_id',$data['test_order_id'])->first();

                $detail ? $detail->delete() : '';

                DB::transaction(function () use ($data,$order) {
                    $details = new TestOrderAssignmentDetail();
                    $details->test_order_assignment_id = $data['test_order_assignment_id'];
                    $details->test_order_id = $data['test_order_id'];
                    $details->note = $data['note']?$data['note']:null;
                    $details->test_order_code = $order->code;
                    $details->save();
                });
                if (isMacro($data['test_order_id'])) {
                    $detailMacro = test_pathology_macro::where('id_test_pathology_order',$data['test_order_id'])->first();
                    $detailMacro->circulation = true;
                    $detailMacro->embedding = true;
                    $detailMacro->microtomy_spreading = true;
                    $detailMacro->staining = true;
                    $detailMacro->mounting = true;
                    $detailMacro->save();
                }else {
                    $macro = new test_pathology_macro();
                    $macro->id_employee = 1;
                    $macro->date = $request->date;
                    $macro->id_test_pathology_order = $data['test_order_id'];
                    $macro->user_id = Auth::user()->id;
                    $macro->circulation = true;
                    $macro->embedding = true;
                    $macro->microtomy_spreading = true;
                    $macro->staining = true;
                    $macro->mounting = true;
                    $macro->save();
                }

                return response()->json(['data'=>$data,'status'=>200],200);
            } else {
                $detail = TestOrderAssignmentDetail::where('test_order_id',$data['test_order_id'])->first();
                if (isMacro($data['test_order_id'])) {
                    $detailMacro = test_pathology_macro::where('id_test_pathology_order',$data['test_order_id'])->first();
                    $detailMacro->circulation = true;
                    $detailMacro->embedding = true;
                    $detailMacro->microtomy_spreading = true;
                    $detailMacro->staining = true;
                    $detailMacro->mounting = true;
                    $detailMacro->save();
                }else {
                    $macro = new test_pathology_macro();
                    $macro->id_employee = 1;
                    $macro->date = $request->date;
                    $macro->id_test_pathology_order =$data['test_order_id'];
                    $macro->user_id = Auth::user()->id;
                    $macro->circulation = true;
                    $macro->embedding = true;
                    $macro->microtomy_spreading = true;
                    $macro->staining = true;
                    $macro->mounting = true;
                    $macro->save();
                }
                return response()->json( ['status'=> 201,'detail'=>$detail], 201);
            }


        } catch (\Throwable $th) {
            return response()->json($th->getMessage(),500);
            //throw $th;
        }
    }

    public function detail_destroy($id)
    {
        $detail = TestOrderAssignmentDetail::find($id);
        $detail->delete();
        return response()->json(200);
    }

    function print($id)
    {
        $assignment = $this->assignment->findorfail($id);
        $details = $assignment->details()->get();
        $setting = Setting::find(1);

        if (empty($assignment)) {
            return back()->with('error', "Cette affectation n'existe pas. Verifiez et réessayez svp ! ");
        }
        // config(['app.name' => $setting->titre]);
        return view('reports.assignment.print', compact('assignment', 'setting', 'details'));
    }

    // Debut
    public function getTestOrdersforDatatable(Request $request)
    {


        $data = $this->assignment->with('details')->latest();

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
                $detail =
                '<a class="btn btn-primary" href="'.route('report.assignment.detail.index',$data->id).'">
                    <i class="uil-eye"></i>
                </a>';
                $deleteBtn = "";

            if ($data->details()->count()>=1) {
                $deleteBtn = '<a class="btn btn-warning" href="'.route('report.assignment.print',$data->id).'">
                    <i class="mdi mdi-printer"></i>
                </a>';
            }


                return $detail.' '.$deleteBtn ;
            })
            ->addColumn('code', function ($data) {
                return $data->code;
            })
            ->addColumn('doctor', function ($data) {
                return $data->user->fullname();
            })
            ->addColumn('date_assignment', function ($data) {
                return dateFormat($data->date);
            })
            ->addColumn('nbr_assignment', function ($data) {
               return $data->details()->count();
            })
            ->filter(function ($query) use ($request,$data) {

                if (!empty($request->get('id_test_pathology_order'))) {
                    // $query->whereHas('id_test_pathology_order', $request->get('id_test_pathology_order'));
                    $query->whereHas('details', function($query) use ($request) {
                        $query->where('test_order_id',$request->get('id_test_pathology_order'));
                    });
                }
                if (!empty($request->get('id_doctor'))) {
                    $query->where('user_id', $request->get('id_doctor'));
                }

                if(!empty($request->get('contenu')))
                {
                    $query->whereHas('details', function($query) use ($request) {
                        $query->where('note','like','%'.$request->get('contenu').'%');
                    })->orwhere('note','like','%'.$request->get('contenu').'%');   
                }

            })
            ->rawColumns(['action','code', 'doctor', 'date_assignment', 'nbr_assignment'])
            ->make(true);
    }

    // Debut
    public function getTestOrdersforDatatable_immuno(Request $request)
    {


        $data = $this->assignment->whereHas('details', function($query) {
            $query->whereHas('order', function($query){
                $query->whereHas('type', function($query){
                    $query->where('slug','immuno-interne')
                            ->orwhere('slug','immuno-exterme')
                            ->where('status', 1) // Statut différent de 0
                            ->whereNull('deleted_at'); // deleted_at doit être NULL
                });
            });
        })->latest();

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
                $detail =
                '<a class="btn btn-primary" href="'.route('report.assignment.detail.index',$data->id).'">
                    <i class="uil-eye"></i>
                </a>';
                $deleteBtn = "";

            if ($data->details()->count()>=1) {
                $deleteBtn = '<a class="btn btn-warning" href="'.route('report.assignment.print',$data->id).'">
                    <i class="mdi mdi-printer"></i>
                </a>';
            }


                return $detail.' '.$deleteBtn ;
            })
            ->addColumn('code', function ($data) {
                return $data->code;
            })
            ->addColumn('doctor', function ($data) {
                return $data->user->fullname();
            })
            ->addColumn('date_assignment', function ($data) {
                return dateFormat($data->created_at);
            })
            ->addColumn('nbr_assignment', function ($data) {
               return $data->details()->count();
            })
            ->filter(function ($query) use ($request,$data) {

                if (!empty($request->get('id_test_pathology_order'))) {
                    // $query->whereHas('id_test_pathology_order', $request->get('id_test_pathology_order'));
                    $query->whereHas('details', function($query) use ($request) {
                        $query->where('test_order_id',$request->get('id_test_pathology_order'));
                    });
                }
                if (!empty($request->get('id_doctor'))) {
                    $query->where('user_id', $request->get('id_doctor'));
                }
            })
            ->rawColumns(['action','code', 'doctor', 'date_assignment', 'nbr_assignment'])
            ->make(true);
    }
}
