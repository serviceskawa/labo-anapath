<?php

namespace App\Http\Controllers;

use App\Models\EmployeeContrat;
use App\Models\EmployeePayroll;
use Illuminate\Http\Request;

class EmployeePayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contrats = EmployeeContrat::latest()->get();
        $payrolls = EmployeePayroll::latest()->get();
        return view('employee_payrolls.index', compact('contrats','payrolls'));
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
                // Créez une nouvelle instance de EmployeePayroll
                $employeePayroll = new EmployeePayroll();

                // Attribuez les valeurs aux colonnes
                $employeePayroll->contrat_employee_id = $request->contrat_employee_id; // Remplacez 1 par l'ID du contrat approprié
                $employeePayroll->monthly_gross_salary = $request->monthly_gross_salary;
                $employeePayroll->hourly_gross_rate = $request->hourly_gross_rate;
                $employeePayroll->transport_allowance = $request->transport_allowance;
                $employeePayroll->iban = $request->iban;
                $employeePayroll->bic = $request->bic;
                // Enregistrez les données dans la base de données
                $employeePayroll->save();

                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeePayroll  $employeePayroll
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeePayroll $employeePayroll)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeePayroll  $employeePayroll
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeePayroll $employeePayroll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeePayroll  $employeePayroll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeePayroll $employeePayroll)
    {
        try {
                // Créez une nouvelle instance de EmployeePayroll
                // $employeePayroll = new EmployeePayroll();

                // Attribuez les valeurs aux colonnes
                $employeePayroll->update([
                    'contrat_employee_id' => $request->contrat_employee_id, 
                    'monthly_gross_salary' => $request->monthly_gross_salary,
                    'hourly_gross_rate' => $request->hourly_gross_rate,
                    'transport_allowance' => $request->transport_allowance,
                    'iban' => $request->iban,
                    'bic' => $request->bic,
                ]);
                // Enregistrez les données dans la base de données
                // $employeePayroll->save();

                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeePayroll  $employeePayroll
     * @return \Illuminate\Http\Response
     */
    public function delete(EmployeePayroll $employeePayroll)
    {
        // if (!getOnlineUser()->can('delete-employee_payrolls')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $employeePayroll->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}