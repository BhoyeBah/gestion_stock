<?php

namespace App\Http\Controllers;

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

        $payments = Payment::where('payment_source', rtrim($type, 's'))->paginate(10);

        return view('back.payments.index', compact('payments', 'type'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $type,Payment $payment)
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
