<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $warehouses = Warehouse::all();

        return view('back.warehouses.index', compact('warehouses'));
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
    public function store(WarehouseRequest $request)
    {
        //
        $warehouse = Warehouse::create([
            'name' => $request->name,
            'address' => $request->address,
            'description' => $request->description,
            'manager_id' => $request->manager_id,
        ]);

        return redirect()->route('warehouses.index')->with('success', 'Entrêpot ajouté avec succès.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        //
        return view("back.warehouses.show", compact("warehouse"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        //
        return view('back.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {
        //
        $warehouse->update($request->validated());

        return redirect()->route('warehouses.index')
            ->with('success', "Les informations de l'entrêpot « {$warehouse->name} » a été mis à jour avec succès !");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        if ($warehouse->is_active) {
            return back()->with('error', 'Impossible de supprimer un entrêpot actif. Veuillez le désactiver d\'abord.');
        }

        //
        $warehouse->delete();

        return back()->with('success', 'Entrêpot supprimé avec succés');
    }

    public function toggleActive(string $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->is_active = ! $warehouse->is_active;
        $warehouse->save();

        // message success
        $message = $warehouse->is_active
            ? 'L\'entrêpot a été activé avec succès.'
            : 'L\'entrêpot a été désactivé avec succès.';

        return redirect()->back()->with('success', $message);
    }
}
