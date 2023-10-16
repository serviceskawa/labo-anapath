<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeContrat;
use App\Models\EmployeeDocument;
use App\Models\EmployeePayroll;
use App\Models\EmployeeTimeoff;
use App\Models\Setting;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected $employee;
    protected $setting;
        public function __construct(Employee $employee, Setting $setting){
        $this->$employee = $employee;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-employees')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $employees = Employee::latest()->get();
        return view('employees.index', compact('employees'));
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
        if (!getOnlineUser()->can('create-employees')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $this->validate($request,[
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|max:50',
            'telephone' => 'required|string',
        ]);


        try {
            $employee = new Employee();

            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->email = $request->email;
            $employee->telephone = $request->telephone;
            $employee->gender = null;
            $employee->date_of_birth = null;
            $employee->place_of_birth = null;
            $employee->nationality = null;
            $employee->cnss_number = null;
            $employee->address = null;
            $employee->city = null;
            $employee->photo_url = null;
            $employee->save();


                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ". $ex);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function details(Employee $employee)
    {
        if (!getOnlineUser()->can('edit-employees')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        try {

            $employees = Employee::latest()->get();
            $conges = EmployeeTimeoff::latest()->get();
            $paies = EmployeePayroll::latest()->get();
            $documents = EmployeeDocument::latest()->get();
            // dd($conges);
            return view('employees.detail', compact('documents','paies','employee','employees','conges'))->with('success', " Opération effectuée avec succès  ! ");
            // return back()->with('success', " Opération effectuée avec succès  ! ");
        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        if (!getOnlineUser()->can('edit-employees')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $this->validate($request,[
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'required|string|max:100',
            'nationality' => 'required|string|max:50',
            'cnss_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:50',
            'photo_url' => 'nullable|image|max:2046',
        ]);

        if ($request->hasFile('photo_url')) {
            $imagePath = $request->file('photo_url')->store('images','public');
        }else{
            $imagePath = $employee->photo_url;
        }


        try {

                $employee->first_name = $request->first_name;
                $employee->last_name = $request->last_name;
                $employee->gender = $request->gender;
                $employee->date_of_birth = $request->date_of_birth;
                $employee->place_of_birth = $request->place_of_birth;
                $employee->nationality = $request->nationality;
                $employee->cnss_number = $request->cnss_number;
                $employee->address = $request->address;
                $employee->city = $request->city;
                $employee->photo_url = $imagePath;

            $employee->save();

                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function delete(Employee $employee)
    {
        if (!getOnlineUser()->can('delete-emloyees')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $employee->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}
