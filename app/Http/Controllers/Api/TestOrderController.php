<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\TestOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TestOrder\TestOrderCollection;

class TestOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = TestOrder::whereHas('type', function ($query) {
            $query->where('slug', 'cytologie')
                ->orwhere('slug', 'histologie')
                ->orwhere('slug', 'biopsie')
                ->orwhere('slug', 'pièce-opératoire')
                ->where('status', 1) // Statut différent de 0
                ->whereNull('deleted_at'); // deleted_at doit être NULL;
        })->where('created_at', '>=', Carbon::now()->subMonths(28))->limit(10)
            ->orderByDesc('created_at')->get();

        return new TestOrderCollection($orders);
    }

    public function searchTestOrder(Request $request)
    {
        $orders = TestOrder::whereHas('type', function ($query) {
            $query->where('slug', 'cytologie')
                ->orwhere('slug', 'histologie')
                ->orwhere('slug', 'biopsie')
                ->orwhere('slug', 'pièce-opératoire')
                ->where('status', 1) // Statut diferente de 0
                ->whereNull('deleted_at'); // deleted_at doit etre NULL;
        })->where('code', 'like', '%' . $request->search . '%')
            ->orderByDesc('created_at')->get();

        return new TestOrderCollection($orders);
    }
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
