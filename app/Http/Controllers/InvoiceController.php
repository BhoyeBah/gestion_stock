<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public InvoiceService $service;

    public function __construct(InvoiceService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $type)
    {
        $this->validateType($type);

        $status_list = ['draft', 'validated', 'partial', 'paid', 'cancelled'];

        $status = $request->input('status');

        $query = in_array($status, $status_list) ? Invoice::type(rtrim($type, 's'))->where('status', $status) : Invoice::type(rtrim($type, 's'));

        $search_number = $request->input('search_number');
        $search_contact = $request->input('search_contact');

        $status = in_array($status, $status_list) ? $status : 'draft';

        if (! empty($search_contact)) {
            $query = $query->whereHas('contact', function ($query) use ($search_contact) {
                $query->where('fullname', 'like', "%$search_contact%")
                    ->orWhere('phone_number', 'like', "%$search_contact%");
            });
        }

        if (! empty($search_number)) {
            $query = $query->where('invoice_number', 'like', "%$search_number%");

        }

        $query = $query->orderBy('created_at', 'desc');

        $invoiceType = $type === 'clients' ? 'Clients' : 'Fournisseurs';

        $invoices = $query->paginate(10);
        $products = Product::all();
        $contacts = Contact::type(rtrim($type, 's'))->get();

        $warehouses = Warehouse::all();

        return view('back.invoices.index', compact('invoices', 'invoiceType', 'type', 'products', 'contacts', 'warehouses'));

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
    public function store(StoreInvoiceRequest $request)
    {
        //
        $path = $request->path();
        $type = $request->type;

        // Vérifie que le type est bien dans le path
        if (! str_contains($path, $type)) {
            abort(403, "Action non autorisée : le type ne correspond pas à l'URL.");
        }
        $this->validateType($type.'s');
        $this->service->createInvoice($request->validated());

        return back()->with('success', 'Facture enregistré avec succés');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $type, string $id)
    {
        //
        $this->validateType($type);
        $invoice = Invoice::with('items')->findOrFail($id);

        $this->checkAuthorization($invoice, $type);

        return view('back.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $type, Invoice $invoice)
    {

        $this->validateType($type);
        $this->checkAuthorization($invoice, $type);

        $products = Product::all();
        $contacts = Contact::type(rtrim($type, 's'))->get();
        $warehouses = Warehouse::all();
        // dd($products, $contacts, $warehouses);

        return view('back.invoices.edit', compact('invoice', 'products', 'warehouses', 'contacts', 'type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreInvoiceRequest $request, string $type, Invoice $invoice)
    {
        //

        $this->validateType($type);
        $this->checkAuthorization($invoice, $type);
        $invoice->delete();
        $new_invoice = $this->service->createInvoice($request->validated());

        return redirect()->route('invoices.edit', [$type, $new_invoice->id])->with('success', 'Facture modifier avec succès');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $type, Invoice $invoice)
    {
        $current_user = auth()->user();

        $error_message = "Vous n'avez pas le droit du supprimer cette facture";
        //
        if ($invoice->status !== 'draft') {
            abort(403, 'Seule les factures en brouillon sont modifiables');
        }

        if ($invoice->type.'s' !== $type) {
            abort(403, $error_message);
        }

        if ($current_user->tenant_id !== $invoice->tenant_id) {
            abort(403, $error_message);
        }
        $invoice->delete();

        return back()->with('success', 'Facture supprimée avec succès');
    }

    public function validateInvoice(string $type, string $id)
    {

        $this->validateType($type);

        $invoice = Invoice::with('items')->findOrFail($id);

        if ($invoice->status !== 'draft') {
            return back()->with('error', 'Cette facture est déjà validée');
        }

        $this->checkAuthorization($invoice, $type);

        try {
            $this->service->validateInvoice($invoice);
            return back()->with('success', 'Facture validée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    protected function validateType(string $type): void
    {
        if (! in_array($type, ['clients', 'suppliers'])) {
            abort(404, 'Page inexistante');
        }
    }

    protected function checkAuthorization(Invoice $invoice, string $type)
    {
        if ($invoice->type !== rtrim($type, 's')) {
            abort(403, "Vous n'êtes pas autorisé à effectuer cette opération.");
        }

    }
}
