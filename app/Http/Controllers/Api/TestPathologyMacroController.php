<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\test_pathology_macro;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TestPathologyMacro\TestPathologyMacroCollection;

class TestPathologyMacroController extends Controller
{
    public function index()
    {

        $slugs = ['cytologie', 'histologie', 'biopsie', 'pièce-opératoire'];

        $macros = test_pathology_macro::with(['order', 'employee', 'user', 'testOrder'])->whereHas('order', function ($query) use ($slugs) {
            $query->whereHas('type', function ($query) use ($slugs) {
                $query->whereIn('slug', $slugs)
                    ->where('status', 1)
                    ->whereNull('deleted_at');
            });
        })
            ->where('created_at', '>=', Carbon::now()->subMonths(3))
            ->orderByDesc('created_at')
            ->get();
        return new TestPathologyMacroCollection($macros);
    }

    public function store(Request $request)
    {
        $orders = $request->orders;
        $macros = [];
        foreach ($orders as $key => $order) {
            $macro = new test_pathology_macro();
            $macro->id_employee = $request->id_employee;
            $macro->date = $request->date;
            $macro->id_test_pathology_order = $order['id'];
            $macro->branch_id = $request->branch_id;
            $macro->user_id = Auth::user()->id;
            $macro->created_at = Carbon::now();
            $macro->save();
            $macros[] = $macro;
        }

        return new TestPathologyMacroCollection($macros);
    }

    public function bulkStore(Request $request)
    {
        $orders = $request->orders;

        $macros = [];
        foreach ($orders as $order) {
            $macro = new test_pathology_macro();
            $macro->id = $order['id'];
            $macro->id_employee = $order['employee_id'];
            $macro->date = Carbon::parse($order['date'])->toDateString();
            $macro->id_test_pathology_order = $order['order_id'];
            $macro->branch_id = $order['branch_id'];
            $macro->user_id = Auth::user()->id;
            $macro->created_at = $order['created_at'];
            $macro->save();
            $macros[] = $macro;
        }

        return new TestPathologyMacroCollection($macros);
    }
}
