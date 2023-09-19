<?php

namespace App\Http\Controllers;

use App\Models\Employee;
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
        $this->validate($request,[
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender' => 'required|string|max:10',
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
            $imagePath = null;
        }

        
        try {

            $employee = new Employee();

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
    public function edit(Employee $employee)
    {
        //
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
        $this->validate($request,[
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender' => 'required|string|max:10',
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
        // if (!getOnlineUser()->can('delete-emloyees')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $employee->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}