@extends('back.layouts.admin')

@section('content')
    <style>
        :root {
            --primary: #0d6efd;
            --muted: #6c757d;
            --card-bg: #ffffff;
        }

        /* Global subtle adjustments */
        .card {
            border-radius: 10px;
        }

        .card-header.bg-primary {
            background-color: var(--primary) !important;
            color: #fff;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        /* === Résumé cards : même hauteur (flex stretch) === */
        .balance-row {
            display: flex;
            gap: .8rem;
            align-items: stretch;
            flex-wrap: wrap;
        }

        .balance-card {
            flex: 1 1 180px;
            display: flex;
            align-items: center;
            background: var(--card-bg);
            border: 1px solid rgba(13, 110, 253, 0.06);
            padding: .75rem 1rem;
            border-radius: 10px;
            transition: transform .14s ease, box-shadow .14s ease;
            box-shadow: 0 1px 6px rgba(15, 23, 42, 0.04);
            min-height: 72px;
        }

        .balance-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        }

        .balance-icon {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-right: .75rem;
            font-size: 1.05rem;
            color: var(--primary);
            background: linear-gradient(180deg, rgba(13, 110, 253, 0.06), rgba(13, 110, 253, 0.02));
            flex: 0 0 44px;
        }

        .balance-text {
            font-size: .85rem;
            color: var(--muted);
        }

        .balance-amount {
            font-size: 1.05rem;
            font-weight: 600;
            color: #0d1b3a;
        }

        .balance-card>div:last-child {
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
        }

        @media (max-width: 768px) {
            .balance-row {
                flex-direction: column;
                align-items: stretch;
            }

            .balance-card {
                width: 100%;
            }
        }

        .nav-tabs .nav-link.active {
            color: var(--primary);
            border-color: transparent transparent var(--primary);
            font-weight: 600;
        }

        .nav-tabs .nav-link {
            color: #495057;
        }

        table.table-hover tbody tr:hover {
            background-color: #fbfdff;
        }

        .muted {
            color: var(--muted);
        }

        .ml-2 {
            margin-left: .5rem;
        }

        .me-2 {
            margin-right: .5rem;
        }
    </style>

    <div class="container-fluid">

        @php
            $totalInvoice = $invoice->items->sum('total_line');
            $totalPaid = $payments->sum('amount_paid');
            $remaining = max(0, $totalInvoice - $totalPaid);
        @endphp

        {{-- Header --}}
        <div class="row align-items-center mb-3">
            <div class="col-12 col-md-4 mb-2 mb-md-0">
                <h2 class="h5 mb-0"><i class="fas fa-file-invoice text-primary"></i> <span class="ml-2">Facture n° <span
                            class="text-primary">{{ $invoice->invoice_number }}</span></span></h2>
                <div class="small text-muted">Créée le :
                    {{ $invoice->created_at ? \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') : '-' }}</div>
            </div>
            <div class="col-12 col-md-4 d-flex justify-content-center">
                <div class="d-flex balance-row" style="max-width:760px; width:100%;">
                    <div class="balance-card">
                        <div class="balance-icon"><i class="fas fa-file-invoice"></i></div>
                        <div>
                            <div class="balance-text">Total facture</div>
                            <div class="balance-amount">{{ number_format($totalInvoice, 0, ',', ' ') }} FCFA</div>
                        </div>
                    </div>
                    <div class="balance-card">
                        <div class="balance-icon"><i class="fas fa-wallet"></i></div>
                        <div>
                            <div class="balance-text">Total payé</div>
                            <div class="balance-amount">{{ number_format($totalPaid, 0, ',', ' ') }} FCFA</div>
                        </div>
                    </div>
                    <div class="balance-card">
                        <div class="balance-icon"><i class="fas fa-coins"></i></div>
                        <div>
                            <div class="balance-text">Reste</div>
                            <div class="balance-amount">{{ number_format($remaining, 0, ',', ' ') }} FCFA</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 text-md-right mt-2 mt-md-0">
                <a href="{{ route('invoices.index', $invoice->type . 's') }}" class="btn btn-outline-primary btn-sm"><i
                        class="fas fa-arrow-left"></i> Retour</a>
            </div>
        </div>

        {{-- Top cards --}}
        <div class="row mb-3">
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary"><i class="fas fa-info-circle me-2"></i>Informations de la facture
                    </div>
                    <div class="card-body p-3">
                        <div class="row mb-2">
                            <div class="col-5 muted">Numéro</div>
                            <div class="col-7">{{ $invoice->invoice_number ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 muted">Date</div>
                            <div class="col-7">
                                {{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') : '-' }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 muted">Échéance</div>
                            <div class="col-7">
                                {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') : '-' }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 muted">Statut</div>
                            <div class="col-7">@php $badge=['draft'=>'secondary','validated'=>'info','partial'=>'warning','paid'=>'success','cancelled'=>'danger'][$invoice->status]??'secondary';@endphp <span
                                    class="badge badge-{{ $badge }}">{{ ucfirst($invoice->status) }}</span></div>
                        </div>
                        <div class="row">
                            <div class="col-5 muted">Note</div>
                            <div class="col-7">{{ $invoice->note ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary"><i
                            class="fas fa-user-tie me-2"></i>{{ ucfirst($invoice->type == 'client' ? 'Client' : 'Fournisseur') }}
                    </div>
                    <div class="card-body p-3">
                        <div class="row mb-2">
                            <div class="col-5 muted">Nom</div>
                            <div class="col-7">{{ optional($invoice->contact)->fullname ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 muted">Téléphone</div>
                            <div class="col-7">{{ optional($invoice->contact)->phone_number ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 muted">Email</div>
                            <div class="col-7">{{ optional($invoice->contact)->email ?? '-' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-5 muted">Adresse</div>
                            <div class="col-7">{{ optional($invoice->contact)->address ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs" id="invoiceTabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="lines-tab" data-toggle="tab" href="#lines"
                            role="tab" aria-controls="lines" aria-selected="true"><i
                                class="fas fa-list mr-1 text-primary"></i> Lignes</a></li>
                    <li class="nav-item"><a class="nav-link" id="payments-tab" data-toggle="tab" href="#payments"
                            role="tab" aria-controls="payments" aria-selected="false"><i
                                class="fas fa-credit-card mr-1 text-primary"></i> Paiements</a></li>
                    <li class="nav-item"><a class="nav-link" id="batches-tab" data-toggle="tab" href="#batches"
                            role="tab" aria-controls="batches" aria-selected="false"><i
                                class="fas fa-boxes mr-1 text-primary"></i> Lots</a></li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="invoiceTabsContent">

                    {{-- TAB Lignes --}}
                    <div class="tab-pane fade show active" id="lines" role="tabpanel" aria-labelledby="lines-tab">
                        @if ($invoice->items->count())
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Entrepôt</th>
                                            <th>Produit</th>
                                            <th class="text-center">Qté</th>
                                            <th class="text-right">Prix U</th>
                                            <th class="text-right">Remise</th>
                                            <th class="text-right">Total</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoice->items as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->warehouse->name ?? '-' }}</td>
                                                <td>{{ $item->product->name ?? '-' }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-right">{{ number_format($item->unit_price, 0, ',', ' ') }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($item->discount ?? 0, 0, ',', ' ') }}
                                                </td>
                                                <td class="text-right">{{ number_format($item->total_line, 0, ',', ' ') }}
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                        data-toggle="modal"
                                                        data-target="#returnModal-{{ $item->id }}"><i
                                                            class="fas fa-undo"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="text-right font-weight-bold">Total Remise</td>
                                            <td class="text-right font-weight-bold">
                                                {{ number_format($invoice->items->sum('discount'), 0, ',', ' ') }}</td>
                                            <td class="text-right">-</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td colspan="2" class="text-right font-weight-bold">Total Général</td>
                                            <td class="text-right font-weight-bold">
                                                {{ number_format($totalInvoice, 0, ',', ' ') }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-light text-center">Aucune ligne enregistrée.</div>
                        @endif
                    </div>

                    {{-- TAB Paiements --}}
                    <div class="tab-pane fade" id="payments" role="tabpanel" aria-labelledby="payments-tab">
                        @if ($payments->count())
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th class="text-right">Montant payé</th>
                                            <th class="text-right">Reste</th>
                                            <th>Type</th>
                                            <th>Source</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $payment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($payment->amount_paid, 0, ',', ' ') }}</td>
                                                <td class="text-right">
                                                    {{ number_format($payment->remaining_amount, 0, ',', ' ') }}</td>
                                                <td>{{ ucfirst($payment->payment_type ?? '-') }}</td>
                                                <td>{{ ucfirst($payment->payment_source ?? '-') }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="2" class="text-right font-weight-bold">Total payé</td>
                                            <td class="text-right font-weight-bold">
                                                {{ number_format($totalPaid, 0, ',', ' ') }}</td>
                                            <td colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-right font-weight-bold">Reste à payer</td>
                                            <td class="text-right font-weight-bold">
                                                {{ number_format($remaining, 0, ',', ' ') }}</td>
                                            <td colspan="3"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 d-flex justify-content-center">{{ $payments->links() }}</div>
                        @else
                            <div class="alert alert-light text-center">Aucun paiement enregistré.</div>
                        @endif
                    </div>

                    {{-- TAB Lots --}}
                    <div class="tab-pane fade" id="batches" role="tabpanel" aria-labelledby="batches-tab">
                        @if ($batches->count())
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Produit</th>
                                            <th class="text-right">Prix</th>
                                            <th class="text-center">Qté initiale</th>
                                            <th class="text-center">Restante</th>
                                            <th class="text-center">Expiration</th>
                                            <th class="text-center">Créé le</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($batches as $batch)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ optional($batch->product)->name ?? '-' }}</td>
                                                <td class="text-right">
                                                    {{ number_format($batch->unit_price, 0, ',', ' ') }}
                                                </td>
                                                <td class="text-center">{{ number_format($batch->quantity, 0, ',', ' ') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($batch->remaining, 0, ',', ' ') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('d/m/Y') : '-' }}
                                                </td>
                                                <td class="text-center">{{ $batch->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 d-flex justify-content-center">{{ $batches->links() }}</div>
                        @else
                            <div class="alert alert-light text-center">Aucun lot enregistré.</div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- Modals Retour produit améliorés - Bootstrap 4 --}}
        @foreach ($invoice->items as $item)
            <div class="modal fade" id="returnModal-{{ $item->id }}" tabindex="-1" role="dialog"
                aria-labelledby="returnModalLabel-{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content border-0 shadow-lg rounded">
                        <form action="{{ route('invoices.returnProduct', [$type, $invoice->id]) }}" method="POST">
                            @csrf

                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="returnModalLabel-{{ $item->id }}">
                                    <i class="fas fa-undo-alt mr-2"></i> Retour produit :
                                    {{ $item->product->name ?? 'Produit' }}
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                {{-- Champ hidden correctement nommé --}}
                                <input type="hidden" name="invoice_item_id" value="{{ $item->id }}">

                                <div class="form-group">
                                    <label class="font-weight-bold">Quantité retournée</label>
                                    <input type="number" name="quantity" class="form-control form-control-lg"
                                        min="1" max="{{ $item->quantity }}" placeholder="Ex: 1" required>
                                    <small class="text-muted">Quantité maximale : {{ $item->quantity }}</small>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Motif</label>
                                    <input type="text" name="motif" class="form-control form-control-lg"
                                        placeholder="Raison du retour" maxlength="255" required>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">
                                        <i class="fas fa-times mr-1"></i> Annuler
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-check mr-1"></i> Valider le retour
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach





    </div>
@endsection
