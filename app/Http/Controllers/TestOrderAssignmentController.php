<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TestOrder;
use App\Models\TestOrderAssignment;
use App\Models\TestOrderAssignmentDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $assignments = $this->assignment->latest()->get();

        return view('reports.assignment.index',compact('assignments'));
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
        $testOrders = $this->order->latest()->get();

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

                return response()->json(['data'=>$data,'status'=>200],200);
            } else {
                $detail = TestOrderAssignmentDetail::where('test_order_id',$data['test_order_id'])->first();
                return response()->json( ['status'=> 201,'detail'=>$detail], 201);
            }


        } catch (\Throwable $th) {
            return response()->json($th->getMessage(),500);
            //throw $th;
        }
    }

    public function detail_destroy($id)
    {
        $detail = $this->details->find($id);
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
}
