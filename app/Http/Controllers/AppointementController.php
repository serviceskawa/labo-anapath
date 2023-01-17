<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        return view('appointement.index', compact('patients', 'doctors'));
    }

    public function getAppointements()
    {
        $data = Appointment::with(['doctor'])->get();

        foreach ($data as $key => $value) {
            $events[$key] = [
                "title" => "test",
                "id" => $value['id'],
                "start" => $value['date'],
                "doctorId" => $value['doctor_id'],
                "doctorName" => $value['doctor']->name,
                "className" => randColor($value['priority']),
            ];
        }
        return response()->json($events);
    }

    public function getAppointementsById(Request $request)
    {
        $data = Appointment::with(['doctor', 'patient'])->whereId($request->id)->first();

        return response()->json($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'doctorId' => 'required',
            'patientId' => 'required',
            'time' => 'required',
            'message' => 'nullable',
            'priority' => 'required',
        ]);

        Appointment::create([
            "doctor_id" => $data['doctorId'],
            "patient_id" => $data['patientId'],
            "priority" => $data['priority'],
            "message" => $data['message'],
            "status" => "pending",
            "date" => "2023-01-17 14:12:22",
        ]);
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $appointement = Appointment::findorfail($id);

        return view('appointement.show', compact('appointement', 'patients', 'doctors'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
