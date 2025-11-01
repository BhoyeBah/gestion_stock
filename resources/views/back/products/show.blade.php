@extends('back.layouts.admin')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    <div class="container-fluid">

        <!-- Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-box-open"></i> Détails du produit
            </h1>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <!-- Card Produit -->
        <div class="card shadow border-left-primary mb-4">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle"></i> Informations sur le produit</h6>
                @if ($product->is_active)
                    <span class="badge badge-success">Activé</span>
                @else
                    <span class="badge badge-danger">Désactivé</span>
                @endif
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Image du produit -->
                    <div class="col-md-4 text-center mb-3">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Image du produit"
                                class="img-fluid rounded shadow-sm" style="max-height: 250px;">
                        @else
                            <img src="{{ asset('images/default-product.png') }}" alt="Image par défaut"
                                class="img-fluid rounded shadow-sm" style="max-height: 250px;">
                        @endif
                    </div>

                    <!-- Détails du produit -->
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Nom :</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Catégorie :</th>
                                    <td>{{ $product->category->name ?? 'Non défini' }}</td>
                                </tr>
                                <tr>
                                    <th>Unité de mesure :</th>
                                    <td>{{ $product->unit->name ?? 'Non défini' }}</td>
                                </tr>
                                <tr>
                                    <th>Prix de vente :</th>
                                    <td>{{ number_format($product->price, 0, ',', ' ') }} CFA</td>
                                </tr>
                                <tr>
                                    <th>Seuil d'alerte :</th>
                                    <td>{{ $product->seuil_alert }}</td>
                                </tr>
                                <tr>
                                    <th>Description :</th>
                                    <td>{{ $product->description ?? 'Aucune description fournie.' }}</td>
                                </tr>
                                <tr>
                                    <th>Date de création :</th>
                                    <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Dernière modification :</th>
                                    <td>{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning mr-2">
                                <i class="fas fa-edit"></i> Modifier
                            </a>

                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                onsubmit="return confirm('Confirmer la suppression de ce produit ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Lots -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
                <div>
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-boxes"></i> Lots ({{ $product->batches->count() }})
                    </h6>
                    <small class="text-white-50">Stocks par lot / entrepôt</small>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Entrepôt</th>
                                <th>Quantité</th>
                                <th>Quantité restante</th>
                                <th>Date d'expiration</th>
                                <th>Ajouté le</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($product->batches as $lot)
                                <tr>
                                    <td>{{ $lot->warehouse->name ?? '-' }}</td>
                                    <td>{{ $lot->quantity }}</td>
                                    <td>
                                        <span class="badge {{ $lot->remaining > 0 ? 'badge-success' : 'badge-danger' }}">
                                            {{ $lot->remaining }}
                                        </span>
                                    </td>
                                    <td>{{ $lot->expiration_date ? Carbon::parse($lot->expiration_date)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>{{ Carbon::parse($lot->created_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucun lot trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section Paiements -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
                <div>
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-credit-card"></i> Paiements
                        ({{ $product->payments->count() }})</h6>
                    <small class="text-white-50">Historique des paiements</small>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Montant payé</th>
                                <th>Montant restant</th>
                                <th>Facture</th>
                                <th>Date de paiement</th>
                                <th>Moyen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($product->payments as $payment)
                                <tr>
                                    <td>{{ number_format($payment->amount_paid, 0, ',', ' ') }} CFA</td>
                                    <td>{{ number_format($payment->remaining_amount, 0, ',', ' ') }} CFA</td>
                                    <td>
                                        @if ($payment->invoice)
                                            <a href="#" class="text-primary">
                                                {{ $payment->invoice->invoice_number ?? '—' }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $payment->payment_date ? Carbon::parse($payment->payment_date)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>{{ ucfirst($payment->payment_type ?? ($payment->payment_source ?? '-')) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucun paiement trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section Factures -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
                <div>
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-file-invoice"></i> Factures
                        ({{ $product->invoices->count() }})</h6>
                    <small class="text-white-50">Factures contenant ce produit</small>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Numéro</th>
                                <th>Client / Fournisseur</th>
                                <th>Type</th>
                                <th>Date facture</th>
                                <th>Date échéance</th>
                                <th>Total</th>
                                <th>Solde</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($product->invoices as $invoice)
                                <tr>
                                    <td>
                                        <a href="#" class="text-primary font-weight-bold">
                                            {{ $invoice->invoice_number ?? '—' }}
                                        </a>
                                    </td>
                                    <td>{{ $invoice->contact->name ?? '-' }}</td>
                                    <td class="text-capitalize">{{ $invoice->type ?? '-' }}</td>
                                    <td>{{ $invoice->invoice_date ? Carbon::parse($invoice->invoice_date)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>{{ $invoice->due_date ? Carbon::parse($invoice->due_date)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>{{ number_format($invoice->total_invoice ?? 0, 0, ',', ' ') }} CFA</td>
                                    <td>{{ number_format($invoice->balance ?? 0, 0, ',', ' ') }} CFA</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Aucune facture trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
