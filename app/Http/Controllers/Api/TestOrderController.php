<?php

namespace App\Http\Controllers\Api;

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
        })->get();

        return new TestOrderCollection($orders);

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
