<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeTimeoff;
use Illuminate\Http\Request;

class EmployeeTimeoffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::latest()->get();
        $timeoffs = EmployeeTimeoff::latest()->get();
        return view('employee_timeoffs.index', compact('timeoffs', 'employees'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
        // Créez une nouvelle instance de EmployeeTimeoff
        $employeeTimeoff = new EmployeeTimeoff();

        // Attribuez les valeurs aux colonnes
        $employeeTimeoff->employee_id = $request->employee_id; // Remplacez 1 par l'ID de l'employé approprié
        $employeeTimeoff->timeoff_type = $request->timeoff_type;
        $employeeTimeoff->start_date = $request->start_date;
        $employeeTimeoff->end_date = $request->end_date;
        $employeeTimeoff->message = $request->message;
        $employeeTimeoff->status = $request->status;

        // Enregistrez les données dans la base de données
        $employeeTimeoff->save();

           return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ".$ex->getMessage());
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeTimeoff  $employeeTimeoff
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeTimeoff $employeeTimeoff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeTimeoff  $employeeTimeoff
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeTimeoff $employeeTimeoff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeTimeoff  $employeeTimeoff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeTimeoff $employeeTimeoff)
    {
        try {

        $employeeTimeoff->update([
            'employee_id' => $request->employee_id,
            'timeoff_type' => $request->timeoff_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'message' => $request->message,
            'status' => $request->status,
        ]);

        if ($request->status == 'active') {
            $employe = Employee::find($employeeTimeoff->employee_id);
            if ($employe->user_id) {
                $employe->user->is_active = 0;
                $employe->user->save();
            }
        }

           return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
    }

    public function updateStatus(Request $request)
    {
        $timeoff = EmployeeTimeoff::find($request->id);
        $timeoff->status = $request->status;
        $timeoff->save();
        if ($request->status == 'active') {
            $employe = Employee::find($timeoff->employee_id);
            if ($employe->user_id) {
                $employe->user->is_active = 0;
                $employe->user->save();
            }
        }
        return response()->json(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeTimeoff  $employeeTimeoff
     * @return \Illuminate\Http\Response
     */
    public function delete(EmployeeTimeoff $employeeTimeoff)
    {
        // if (!getOnlineUser()->can('delete-employee_timeoffs')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $employeeTimeoff->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}
