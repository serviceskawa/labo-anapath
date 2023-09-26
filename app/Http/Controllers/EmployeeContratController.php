<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeContrat;
use App\Models\EmployeePayroll;
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
        // dd($request);
        $this->validate($request, [
            'employee_id' => 'required|exists:employees,id',
            'contract_type' => 'required|string|max:50|nullable',
            'weekly_work_hours' => 'integer|nullable',
            'start_date' => 'required|date|nullable',
            'end_date' => 'date|nullable',
            'termination_reason' => 'string|max:255|nullable',
            'probation_end_date' => 'date|nullable',
            'working_days_per_week' => 'integer|nullable',
        ]);
        
        
         try {
                

             $emp_contrat = EmployeeContrat::create([
                    'employee_id' => $request->employee_id,
                    'contract_type' => $request->contract_type ? $request->contract_type : null,
                    'weekly_work_hours' => $request->weekly_work_hours ? $request->weekly_work_hours : null,
                    'start_date' => $request->start_date ? $request->start_date : null,
                    'end_date' => $request->end_date ? $request->end_date : null,
                    'termination_reason' => $request->termination_reason ? $request->termination_reason : null,
                    'probation_end_date' => $request->probation_end_date ? $request->probation_end_date : null,
                    'working_days_per_week' => $request->working_days_per_week ? $request->working_days_per_week : null,
                ]);



                $employeePayroll = new EmployeePayroll();

                // Attribuez les valeurs aux colonnes
                $employeePayroll->employee_contrat_id = $emp_contrat->id; 
                $employeePayroll->monthly_gross_salary = $request->monthly_gross_salary ? $request->monthly_gross_salary : null;
                $employeePayroll->hourly_gross_rate = $request->hourly_gross_rate ? $request->hourly_gross_rate : null;
                $employeePayroll->transport_allowance = $request->transport_allowance ? $request->transport_allowance : null;
                $employeePayroll->iban = $request->iban ? $request->iban : null;
                $employeePayroll->bic = $request->bic ? $request->bic : null;
                // Enregistrez les données dans la base de données
                $employeePayroll->save();

                
            // dd($employeePayroll);
                
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
    public function edit(EmployeePayroll $employeeContrat)
    {
        // dd($employeeContrat);
        return view('employee_contrats.edit',compact('employeeContrat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeContrat  $employeeContrat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$employeePayroll)
    {
        $this->validate($request, [
            'employee_id' => 'required|exists:employees,id',
            'contract_type' => 'required|string|max:50|nullable',
            'weekly_work_hours' => 'integer|nullable',
            'start_date' => 'required|date|nullable',
            'end_date' => 'date|nullable',
            'termination_reason' => 'string|max:255|nullable',
            'probation_end_date' => 'date|nullable',
            'working_days_per_week' => 'integer|nullable',
        ]);
        $paie = EmployeePayroll::find($employeePayroll);
        $contrat = EmployeeContrat::find($paie->employee_contrat_id);
       
         try {
                $contrat->update([
                    'employee_id' => $request->employee_id,
                    'contract_type' => $request->contract_type ? $request->contract_type : null,
                    'weekly_work_hours' => $request->weekly_work_hours ? $request->weekly_work_hours : null,
                    'start_date' => $request->start_date ? $request->start_date : null,
                    'end_date' => $request->end_date ? $request->end_date : null,
                    'termination_reason' => $request->termination_reason ? $request->termination_reason : null,
                    'probation_end_date' => $request->probation_end_date ? $request->probation_end_date : null,
                    'working_days_per_week' => $request->working_days_per_week ? $request->working_days_per_week : null,
                ]);
                



                // $employeePayroll = new EmployeePayroll();

                // Attribuez les valeurs aux colonnes
                // $paie->employee_contrat_id = $paie->employee_contrat_id; 
                $paie->monthly_gross_salary = $request->monthly_gross_salary ? $request->monthly_gross_salary : null;
                $paie->hourly_gross_rate = $request->hourly_gross_rate ? $request->hourly_gross_rate : null;
                $paie->transport_allowance = $request->transport_allowance ? $request->transport_allowance : null;
                $paie->iban = $request->iban ? $request->iban : null;
                $paie->bic = $request->bic ? $request->bic : null;
                // Enregistrez les données dans la base de données
                $paie->save();

                // return redirect(route('employee.detail'))->with('success', " Opération effectuée avec succès  ! ");
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