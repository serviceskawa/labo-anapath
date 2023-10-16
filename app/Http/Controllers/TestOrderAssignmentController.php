<?php

namespace App\Http\Controllers;

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

    public function index_detail($id)
    {
        $assignment = $this->assignment->find($id);
        $details = $this->details->where('test_order_assignment_id', $assignment->id)->get();
        return response()->json($details);
    }

    public function store(Request $request)
    {
        // dd($request);
        try {
            $assignment = $this->assignment->create([
                'user_id' => $request->user_id
            ]);
            $testOrders = $this->order->latest()->get();

            return view('reports.assignment.create',compact('assignment','testOrders'));

        } catch (\Throwable $th) {
            return back()->with('error', "Erreur de'enregistrement".$th->getMessage());
        }
    }

    public function store_detail(Request $request)
    {
        $data = [
            'test_order_assignment_id' => $request->test_order_assignment_id,
            'test_order_id' => $request->test_order_id,
            'note' => $request->note,
        ];
        try {
            DB::transaction(function () use ($data) {
                $details = new TestOrderAssignmentDetail();
                $details->test_order_assignment_id = $data['test_order_assignment_id'];
                $details->test_order_id = $data['test_order_id'];
                $details->note = $data['note']?$data['note']:null;
                $details->save();
            });

            return response()->json($data,200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(),500);
            //throw $th;
        }
    }
}
