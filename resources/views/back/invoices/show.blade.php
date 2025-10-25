@extends('back.layouts.admin')

@section('content')
    <div class="container">
        {{-- Header --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-invoice"></i> Détails de la facture
            </h1>
            <a href="{{ route('invoice_suppliers.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

        {{-- Row Fournisseur + Facture --}}
        <div class="row d-flex align-items-stretch">

            {{-- Bloc facture --}}
            <div class="col-md-6 mb-4 d-flex">
                <div class="card shadow border-left-info w-100">
                    <div class="card-header bg-info text-white">
                        <h6 class="m-0 font-weight-bold">Informations de la facture</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped mb-0">
                            <tbody>
                                <tr>
                                    <th>Numéro de facture</th>
                                    <td>{{ $invoice->invoice_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Entrepôt</th>
                                    <td>{{ $invoice->warehouse->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Date de facture</th>
                                    <td>{{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date d'échéance</th>
                                    <td>{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @php
                                            $statusColor =
                                                [
                                                    'DRAFT' => 'secondary',
                                                    'PARTIALLY_PAID' => 'warning',
                                                    'PAID' => 'success',
                                                    'CANCELLED' => 'danger',
                                                ][$invoice->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $statusColor }}">{{ $invoice->status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Note</th>
                                    <td>{{ $invoice->note ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Bloc fournisseur --}}
            <div class="col-md-6 mb-4 d-flex">
                <div class="card shadow border-left-primary w-100">
                    <div class="card-header bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">Informations du fournisseur</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped mb-0">
                            <tbody>
                                <tr>
                                    <th>Nom complet</th>
                                    <td>{{ $invoice->supplier->full_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Téléphone</th>
                                    <td>{{ $invoice->supplier->phone_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $invoice->supplier->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Adresse</th>
                                    <td>{{ $invoice->supplier->address ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- Ligne des produits --}}
        {{-- Ligne des produits --}}
        <div class="card shadow border-left-primary mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">Lignes de la facture</h6>
            </div>
            <div class="card-body">
                @if ($invoice->items->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light text-uppercase text-secondary small">
                                <tr>
                                    <th>#</th>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Prix unitaire (FCFA)</th>
                                    <th>Discount (FCFA)</th>
                                    <th>Total ligne (FCFA)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->product->name ?? '-' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->purchase_price, 0, ',', ' ') }}</td>
                                        <td>{{ number_format($item->discount ?? 0, 0, ',', ' ') }}</td>
                                        <td>{{ number_format($item->total_line, 0, ',', ' ') }}</td>
                                    </tr>
                                @endforeach
                                {{-- Total Discount --}}
                                <tr class="font-weight-bold bg-light">
                                    <td colspan="4" class="text-right">Total Discount</td>
                                    <td>{{ number_format($invoice->items->sum(fn($i) => $i->discount ?? 0), 0, ',', ' ') }}
                                    </td>
                                    <td>-</td>
                                </tr>
                                {{-- Total Général --}}
                                <tr class="font-weight-bold bg-secondary text-white">
                                    <td colspan="5" class="text-right">Total Général (après remise)</td>
                                    <td>{{ number_format($invoice->items->sum(fn($i) => $i->total_line), 0, ',', ' ') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Aucune ligne de produit pour cette facture.
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
