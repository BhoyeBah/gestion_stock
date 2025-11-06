@php
    $total_invoices = $contact->invoices->count();
    $total_paid = $contact->invoices->sum(function ($invoice) {
        return $invoice->payments->sum('amount_paid');
    });
    $total_balance = $contact->invoices->sum('balance');
    $total_invoice_amount = $contact->invoices->sum('total_invoice');
@endphp
@extends('back.layouts.admin')
@section('content')
    {{-- Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-tie"></i> Détails du {{ $type === 'clients' ? 'client' : 'fournisseur' }}
        </h1>
        <a href="{{ route($type . '.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    {{-- Stats --}}
    <div class="row mb-4">
        {{-- Nombre de factures --}}
        <div class="col-md-3 mb-2">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Factures</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_invoices }}</div>
                    </div>
                    <div class="text-gray-300">
                        <i class="fas fa-file-invoice fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total payé --}}
        <div class="col-md-3 mb-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Payé</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($total_paid, 0, ',', ' ') }}
                            FCFA</div>
                    </div>
                    <div class="text-gray-300">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total restant --}}
        <div class="col-md-3 mb-2">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Reste à payer</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($total_balance, 0, ',', ' ') }}
                            FCFA</div>
                    </div>
                    <div class="text-gray-300">
                        <i class="fas fa-exclamation-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Solde total --}}
        <div class="col-md-3 mb-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Montant total</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($total_invoice_amount, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="text-gray-300">
                        <i class="fas fa-coins fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Informations du contact --}}
    <div class="card shadow border-left-primary mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Informations du {{ $type === 'clients' ? 'client' : 'fournisseur' }}</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>Nom complet</th>
                        <td>{{ $contact->fullname }}</td>
                    </tr>
                    <tr>
                        <th>Téléphone</th>
                        <td>{{ $contact->phone_number }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $contact->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Adresse</th>
                        <td>{{ $contact->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if ($contact->is_active)
                                <span class="badge badge-success">Activé</span>
                            @else
                                <span class="badge badge-danger">Désactivé</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Date de création</th>
                        <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Dernière mise à jour</th>
                        <td>{{ $contact->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Factures --}}
    <div class="card shadow border-left-info mb-4">
        <div class="card-header bg-info text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-file-invoice"></i> Factures
            </h6>
        </div>
        <div class="card-body">
            @if ($contact->invoices->count())
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Numéro</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Balance</th>
                                <th>Nombre de paiements</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contact->invoices as $invoice)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('invoices.show', [$invoice->type.'s', $invoice->id]) }}">
                                            {{ $invoice->invoice_number }}
                                        </a>
                                    </td>
                                    <td>{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                    <td>{{ number_format($invoice->total_invoice, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ number_format($invoice->balance, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ $invoice->payments->count() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paiements --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Facture</th>
                                <th>Date du paiement</th>
                                <th>Montant payé</th>
                                <th>Reste à payer</th>
                                <th>Type de paiement</th>
                                <th>Source</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $count = 1; @endphp
                            @foreach ($contact->invoices as $invoice)
                                {{-- Ligne d'entête de facture --}}
                                <tr class="table-primary">
                                    <td colspan="7">
                                        Facture : <a href="{{ route("invoices.show", [$invoice->type.'s', $invoice->id]) }}">{{ $invoice->invoice_number }}</a> - Total :
                                        {{ number_format($invoice->total_invoice, 0, ',', ' ') }} FCFA - Balance :
                                        {{ number_format($invoice->balance, 0, ',', ' ') }} FCFA
                                    </td>
                                </tr>

                                {{-- Paiements de cette facture --}}
                                @foreach ($invoice->payments as $payment)
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>
                                            <a href="{{ route("invoices.show", [$invoice->type.'s', $invoice->id]) }}">{{ $invoice->invoice_number }}</a>
                                        </td>
                                        <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                        <td>{{ number_format($payment->amount_paid, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ number_format($payment->remaining_amount, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ ucfirst($payment->payment_type) }}</td>
                                        <td>{{ ucfirst($payment->payment_source) }}</td>
                                    </tr>
                                @endforeach

                                {{-- Ligne vide entre deux factures pour mieux visualiser --}}
                                <tr>
                                    <td colspan="7"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Aucune facture trouvée pour ce contact.
                </div>
            @endif
        </div>
    </div>
@endsection
