@extends('back.layouts.admin')

@section('content')
    @php
        use Carbon\Carbon;

        $invoiceItems = $product->invoiceItems;

        // Totaux
        $totalOut = $invoiceItems->where('type', 'out')->sum('quantity');
        $totalIn = $invoiceItems->where('type', 'in')->sum('quantity');

        $totalValueSold = $invoiceItems
            ->where('type', 'out')
            ->sum(fn($item) => $item->quantity * $item->unit_price - $item->discount);

        $totalValueIn = $invoiceItems
            ->where('type', 'in')
            ->sum(fn($item) => $item->quantity * $item->unit_price - $item->discount);

        // Moyennes
        $averagePriceOut = $invoiceItems->where('type', 'out')->avg('unit_price') ?: 0;
        $averagePriceIn = $invoiceItems->where('type', 'in')->avg('unit_price') ?: 0;

        // Quantité expirée
        $expiredQuantity = $product->batches->where('expiration_date', '<', now())->sum('quantity');

        // Total discount
        $totalDiscount = $invoiceItems->where('type', 'out')->sum('discount');

        // Quantité restante réelle
        $totalRemaining = $product->batches->sum('remaining');

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

        <!-- Statistiques rapides -->
        <div class="d-flex flex-wrap mb-4">
            @php
                $stats = [
                    ['label' => 'Total vendu', 'value' => $totalOut, 'icon' => 'shopping-cart', 'color' => 'info'],
                    ['label' => 'Stock actuel', 'value' => $totalRemaining, 'icon' => 'boxes', 'color' => 'primary'],
                    [
                        'label' => 'Valeur totale vendue',
                        'value' => number_format($totalValueSold, 0, ',', ' ') . ' CFA',
                        'icon' => 'dollar-sign',
                        'color' => 'dark',
                    ],
                    [
                        'label' => 'Valeur totale achat',
                        'value' => number_format($totalValueIn, 0, ',', ' ') . ' CFA',
                        'icon' => 'credit-card',
                        'color' => 'success',
                    ],
                    [
                        'label' => 'Nb factures',
                        'value' => $product->invoices->count(),
                        'icon' => 'file-invoice',
                        'color' => 'secondary',
                    ],
                    [
                        'label' => 'Prix moyen vente',
                        'value' => number_format($averagePriceOut, 0, ',', ' ') . ' CFA',
                        'icon' => 'tag',
                        'color' => 'info',
                    ],
                    [
                        'label' => 'Prix moyen achat',
                        'value' => number_format($averagePriceIn, 0, ',', ' ') . ' CFA',
                        'icon' => 'tag',
                        'color' => 'success',
                    ],
                    [
                        'label' => 'Quantité expirée',
                        'value' => $expiredQuantity,
                        'icon' => 'exclamation-triangle',
                        'color' => 'danger',
                    ],
                    [
                        'label' => 'Réduction totale',
                        'value' => number_format($totalDiscount, 0, ',', ' ') . ' CFA',
                        'icon' => 'percent',
                        'color' => 'warning',
                    ],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div class="col-md-3 mb-2">
                    <div class="card border-left-{{ $stat['color'] }} shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center">
                            <div class="mr-3"><i class="fas fa-{{ $stat['icon'] }} fa-2x text-{{ $stat['color'] }}"></i>
                            </div>
                            <div>
                                <div class="text-xs font-weight-bold text-{{ $stat['color'] }} text-uppercase mb-1">
                                    {{ $stat['label'] }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stat['value'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
                                    <td>
                                        <a href="{{ route('categories.index', $product->category->id) }}">
                                            {{ $product->category->name ?? 'Non défini' }}
                                        </a>
                                    </td>
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
                                    <td>
                                        <a href="{{ route('warehouses.show', $lot->warehouse->id) }}">
                                            {{ $lot->warehouse->name ?? '-' }}
                                        </a>
                                    </td>
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
                                        <a href="{{ route('invoices.show', [$invoice->type . 's', $invoice->id ?? '#']) }}"
                                            class="text-primary font-weight-bold">
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

        <!-- Section Mouvements d'Inventaire -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
                <div>
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-exchange-alt"></i> Mouvements d'inventaire</h6>
                    <small class="text-white-50">Historique des mouvements</small>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Quantité</th>
                                <th>Raison</th>
                                <th>Lot</th>
                                <th>Entrepôt</th>
                                <th>Facture</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($product->movement()->latest()->paginate(10) as $movement)
                                <tr>
                                    <td>{{ $movement->quantity }}</td>
                                    <td>{{ $movement->reason ?? '-' }}</td>
                                    <td>{{ $movement->batch->name ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('warehouses.show', $movement->batch->warehouse->id) }}">
                                            {{ $movement->batch->warehouse->name ?? '-' }}
                                        </a>
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('invoices.show', [$movement->invoice->type . 's', $movement->invoice->id ?? '#']) }}">
                                            {{ $movement->invoice->invoice_number ?? '-' }}

                                        </a>
                                    </td>
                                    <td>{{ Carbon::parse($movement->created_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Aucun mouvement trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $product->movement()->latest()->paginate(10)->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
