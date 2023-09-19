<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeContrat;
use App\Models\Setting;
use Illuminate\Http\Request;

class EmployeeContratController extends Controller
{
    protected $employeeContrat;
    protected $setting;
        public function __construct(EmployeeContrat $employeeContrat, Setting $setting){
        $this->$employeeContrat = $employeeContrat;
        $this->setting = $setting;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employes_contrats = EmployeeContrat::latest()->get();
        $employees = Employee::latest()->get();
        return view('employee_contrats.index', compact('employees','employes_contrats'));
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
        $this->validate($request, [
            'employee_id' => 'required|exists:employees,id',
            'contract_type' => 'required|string|max:50',
            'weekly_work_hours' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'date',
            'termination_reason' => 'string|max:255',
            'probation_end_date' => 'date',
            'working_days_per_week' => 'required|integer',
        ]);

         try {
             EmployeeContrat::create([
                 'employee_id' => $request->employee_id,
                 'contract_type' => $request->contract_type,
                 'weekly_work_hours' => $request->weekly_work_hours,
                 'start_date' => $request->start_date,
                 'end_date' => $request->end_date,
                 'termination_reason' => $request->termination_reason,
                 'probation_end_date' => $request->probation_end_date,
                 'working_days_per_week' => $request->working_days_per_week,
                ]);
                
                
                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeContrat  $employeeContrat
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeContrat $employeeContrat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeContrat  $employeeContrat
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeContrat $employeeContrat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeContrat  $employeeContrat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeContrat $employeeContrat)
    {
        $this->validate($request, [
            'employee_id' => 'required|exists:employees,id',
            'contract_type' => 'required|string|max:50',
            'weekly_work_hours' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'date',
            'termination_reason' => 'string|max:255',
            'probation_end_date' => 'date',
            'working_days_per_week' => 'required|integer',
        ]);

         try {
                $employeeContrat->update([
                 'employee_id' => $request->employee_id,
                 'contract_type' => $request->contract_type,
                 'weekly_work_hours' => $request->weekly_work_hours,
                 'start_date' => $request->start_date,
                 'end_date' => $request->end_date,
                 'termination_reason' => $request->termination_reason,
                 'probation_end_date' => $request->probation_end_date,
                 'working_days_per_week' => $request->working_days_per_week,
                ]);
                
                
                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeContrat  $employeeContrat
     * @return \Illuminate\Http\Response
     */
    public function delete(EmployeeContrat $employeeContrat)
    {
        // if (!getOnlineUser()->can('delete-emloyee_contrats')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $employeeContrat->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}