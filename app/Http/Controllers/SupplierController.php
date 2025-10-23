<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $suppliers = Supplier::all();

        return view('back.suppliers.index', compact('suppliers'));
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
    public function store(SupplierRequest $request)
    {
        //
        Supplier::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur ajouté avec succès.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
        return view('back.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
        return view('back.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {

        // Remplir les champs avec les données validées
        $supplier->update($request->validated());

        // Redirection avec message
        return redirect()->route('suppliers.index')
            ->with('success', "Les informations fournisseur « {$supplier->full_name} » a été mis à jour avec succès !");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        if ($supplier->is_active) {
            return back()->with('error', 'Impossible de supprimer un fournisseur actif. Veuillez le désactiver d\'abord.');
        }

        $supplier->delete();

        return back()->with('success', 'Fournisseur supprimé avec succès.');
    }

    public function toggleActive(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->is_active = ! $supplier->is_active;
        $supplier->save();

        // message success
        $message = $supplier->is_active
            ? 'Le fournisseur a été activé avec succès.'
            : 'Le fournisseur a été désactivé avec succès.';

        return redirect()->back()->with('success', $message);
    }
}
