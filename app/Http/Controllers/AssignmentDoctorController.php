<?php

namespace App\Http\Controllers;

use App\Models\AssignmentDoctor;
use App\Models\Report;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class AssignmentDoctorController extends Controller
{
    public function index()
    {
       $doctors = getUsersByRole('docteur');
        return view('reports.assignment.index',compact('doctors'));
    }

    public function create($id)
    {
        $reports = Report::latest()->get();
        $doctor = User::find($id);
        // dd($doctor);
        return view('reports.assignment.create',compact('reports','doctor'));
    }

    public function store(Request $request)
    {
        dd($request);
    }
}
