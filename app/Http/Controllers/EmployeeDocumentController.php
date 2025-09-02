<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDocument;
use App\Models\Setting;
use Illuminate\Http\Request;

class EmployeeDocumentController extends Controller
{
    protected $employeeDocument;
    protected $setting;
    public function __construct(EmployeeDocument $employeeDocument, Setting $setting)
    {
        $this->$employeeDocument = $employeeDocument;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $this->validate($request, [
            'name_file' => 'required|string|max:255',
            // 'file' => 'required|image|max:2046',
        ]);

        if ($request->hasFile('file')) {
            $imagePath = $request->file('file')->store('documents', 'public');
        } else {
            $imagePath = null;
        }

        try {

            $document = new EmployeeDocument();
            $document->name_file = $request->name_file;
            $document->file = $imagePath;
            $document->employee_id = $request->employee_id;
            $document->save();

            return back()->with('success', " Opération effectuée avec succès  ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeDocument $employeeDocument)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeDocument $employeeDocument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeDocument $employeeDocument)
    {
        if (!getOnlineUser()->can('edit-employees')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $this->validate($request, [
            'name_file' => 'required|string|max:255',
        ]);

        if ($request->hasFile('file')) {
            $imagePath = $request->file('file')->store('documents', 'public');
        } else {
            $imagePath = $employeeDocument->file;
        }

        try {
            $employeeDocument->update([
                'name_file' => $request->name_file,
                'file' => $imagePath,
            ]);

            return back()->with('success', " Opération effectuée avec succès  ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return \Illuminate\Http\Response
     */
    public function delete(EmployeeDocument $employeeDocument)
    {
        //  if (!getOnlineUser()->can('delete-employees')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $employeeDocument->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}
