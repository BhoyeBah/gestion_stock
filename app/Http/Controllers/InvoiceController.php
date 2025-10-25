<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceSupplierRequest;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $service;

    public function __construct(InvoiceService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['supplier', 'warehouse', 'items.product'])->latest();

        $perPage = 10;
        $perPageRequest = $request->perPage;

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($perPageRequest && $perPageRequest > 0 && $perPageRequest < 50) {
            $perPage = $perPageRequest;
        }

        // Pagination
        $invoices = $query->paginate($perPage);

        $suppliers = Supplier::all();
        $warehouses = Warehouse::all();
        $products = Product::all();

        return view('back.invoices.index', compact('invoices', 'products', 'suppliers', 'warehouses'));
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
    public function store(StoreInvoiceSupplierRequest $request)
    {
        //

        $data = $request->all();

        $this->service->createInvoice($data);

        return back()->with('success', 'La facture a bien été enregistré');

    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
        return view('back.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->status !== 'DRAFT') {
            return back()->with('error', 'Seules les factures en brouillon peuvent être supprimées.');
        }

        $invoice->delete();

        return back()->with('success', 'Facture supprimée avec succès.');
    }
    
}
