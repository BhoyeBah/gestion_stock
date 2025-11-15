<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $type)
    {
        //

        $this->validateType($type);

        $payments = Payment::with(['invoice', 'contact'])
            ->where('payment_source', rtrim($type, 's'))
            ->paginate(10);

        // Charger les factures du même type (client ou supplier)
        $invoices = Invoice::where('type', rtrim($type, 's'))
            ->orderBy('invoice_number', 'desc')
            ->where('balance', '>', 0)
            ->get();

        return view('back.payments.index', compact('payments', 'type', 'invoices'));
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
    public function store(PaymentRequest $request)
    {
        //
        $invoice = Invoice::findOrFail($request->invoice_id);
        $amountPaid = (int) $request->input('amount_paid');

        if ($amountPaid > $invoice->balance) {
            return back()->with('error', "Montant trop élevé. Solde restant : {$invoice->balance} FCFA");
        }

        try {
            DB::beginTransaction();
            $invoice->balance -= $amountPaid;
            if ($invoice->balance > 0) {
                $invoice->status = 'partial';

            } elseif ($invoice->balance == 0) {
                $invoice->status = 'paid';
            }
            $invoice->save();
            Payment::create([
                'invoice_id' => $invoice->id,
                'tenant_id' => $invoice->tenant_id,
                'contact_id' => $invoice->contact_id,
                'amount_paid' => $amountPaid,
                'remaining_amount' => $invoice->balance,
                'payment_date' => now(),
                'payment_type' => $request->input('payment_type'),
                'payment_source' => $invoice->type,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Erreur lors du paiement : '.$e->getMessage());
        }

        return back()->with('success', "Paiement de $amountPaid FCFA de la facture numéro $invoice->invoice_number enregistré avec succès !");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $type, Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $type, Payment $payment)
    {

        try {

            DB::beginTransaction();

            if ($payment->invoice) {

                $invoice = $payment->invoice;

                if ($payment->amount_paid <= 0) {
                    return back()->with('error', 'Vous ne pouvez pas supprimer la facture initial');
                }

                // Remboursement du solde
                $invoice->balance += $payment->amount_paid;

                // Mettre à jour le statut selon le nouveau solde
                if ($invoice->balance >= $invoice->total_invoice) {
                    $invoice->status = 'partial';
                } elseif ($invoice->balance > 0) {
                    $invoice->status = 'partial';
                } else {
                    $invoice->status = 'paid';
                }

                $invoice->save();
            }

            $payment->delete();

            DB::commit();

            return back()->with('success', 'Paiement supprimé et solde de la facture réajusté avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Erreur lors de la suppression du paiement : '.$e->getMessage());
        }

    }

    protected function validateType(string $type): void
    {
        if (! in_array($type, ['clients', 'suppliers'])) {
            abort(404, 'Page inexistante');
        }
    }
}
