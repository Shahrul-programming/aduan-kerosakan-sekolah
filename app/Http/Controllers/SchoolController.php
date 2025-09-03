<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = \App\Models\School::all();
        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:schools',
            'address' => 'required',
            'principal_name' => 'required',
            'principal_phone' => 'required',
            'hem_name' => 'required',
            'hem_phone' => 'required',
        ]);
        \App\Models\School::create($request->all());
        return redirect()->route('schools.index')->with('success', 'Sekolah berjaya ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $school = \App\Models\School::findOrFail($id);
        return view('schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $school = \App\Models\School::findOrFail($id);
        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $school = \App\Models\School::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:schools,code,' . $school->id,
            'address' => 'required',
            'principal_name' => 'required',
            'principal_phone' => 'required',
            'hem_name' => 'required',
            'hem_phone' => 'required',
        ]);
        $school->update($request->all());
        return redirect()->route('schools.index')->with('success', 'Sekolah berjaya dikemaskini');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $school = \App\Models\School::findOrFail($id);
        $school->delete();
        return redirect()->route('schools.index')->with('success', 'Sekolah berjaya dipadam');
    }
}
