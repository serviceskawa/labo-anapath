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
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
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
            $macro->user_id = Auth::user()->id;
            $macro->save();
            $macros[] = $macro;
        }

        return new TestPathologyMacroCollection($macros);
    }
}
