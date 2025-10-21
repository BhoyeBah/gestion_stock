<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UnitRequest;
use App\Models\Units;



class UnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $units = Units::all();
        return view("back.admin.units.index",compact("units"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitRequest $request)
    {
        //
       Units::create([

        "name"=>$request->name,
        "code"=>$request->code,

       ]);

       return back()->with("success", "Unité ajouté avec succés");

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Units $unit)
    {
        //
        return view("back.admin.units.edit", compact("unit"));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitRequest $request, Units $unit)
    {
        //
        $unit->name = $request->name;
        $unit->code = $request->code;
        $unit->save();

       return redirect()->route('admin.units.index')->with("success", "Unité modifié avec succés");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Units $unit)
    {
        $unit->delete();
        return back()->with("success", "unité supprimé avec succés");
    }
}
