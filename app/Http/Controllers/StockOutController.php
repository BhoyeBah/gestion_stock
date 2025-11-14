<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $stockOuts = StockOut::paginate(10);

        return view('back.stockOut.index', compact('stockOuts'));
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
    public function store(Request $request)
    {
        $currentUser = auth()->user();
        $batch = Batch::findOrFail($request->batch_id);
        $quantityOut = (int) $request->input('quantity');
        $reason = $request->input('reason');

        if ($quantityOut > $batch->remaining) {
            return back()->with('error', 'La quantité demandée dépasse le stock disponible.');
        }

        StockOut::create([
            'tenant_id' => $currentUser->tenant_id,
            'batch_id' => $batch->id,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
        ]);

        $batch->remaining -= $request->quantity;
        $batch->save();

        return back()->with('success', 'Sortie de stock enrégistrée avec succées');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockOut $stockOut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockOut $stockOut)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockOut $stockOut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $stockOut = StockOut::findOrFail($id);
            $batch = $stockOut->batch;

            if ($batch) {
                // Restaurer la quantité dans le lot
                $batch->remaining += $stockOut->quantity;
                $batch->save();
            }

            $stockOut->delete();
        });

        return back()->with('success', 'Sortie supprimée et stock restauré.');
    }
}
