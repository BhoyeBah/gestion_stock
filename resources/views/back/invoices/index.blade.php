@extends('back.layouts.admin')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-invoice"></i> Factures {{ $invoiceType }}
        </h1>

        <div>
            <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addContactModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau {{ $type === 'clients' ? 'client' : 'fournisseur' }}
            </button>

            <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addWarehouseModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Nouvel entrepôt
            </button>

            <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addproductModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau produit
            </button>

            <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addInvoiceModal">
                <i class="fas fa-plus fa-sm text-white-50"></i>
                Nouvelle facture
            </button>
        </div>
    </div>

    <!-- =========================
                                     CARTES STATISTIQUES PAR STATUT
                                ========================= -->
    <div class="row mb-4">
        @php
            $statuses = [
                'draft' => ['label' => 'Brouillon', 'color' => 'secondary'],
                'validated' => ['label' => 'Validée', 'color' => 'info'],
                'partial' => ['label' => 'Partielle', 'color' => 'warning'],
                'paid' => ['label' => 'Payée', 'color' => 'success'],
                'cancelled' => ['label' => 'Annulée', 'color' => 'danger'],
            ];
        @endphp

        @foreach ($statuses as $key => $status)
            <div class="col-md-2 mb-3">
                <div class="card border-left-{{ $status['color'] }} shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-{{ $status['color'] }} text-uppercase mb-1">
                                    {{ $status['label'] }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $allInvoices->where('status', $key)->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Total général -->
        <div class="col-md-2 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $allInvoices->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- =========================
                                                         SECTION 1 : RECHERCHE ET FILTRES
                                                    ========================= -->
    <section id="invoice-filters">
        <div class="card shadow border-left-info mb-4">
            <div class="card-header bg-info text-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-search"></i> Recherche et filtres
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('invoices.index', $type) }}">
                    <div class="form-row align-items-end">
                        <div class="col-md-3">
                            <label for="search_number" class="small text-muted">Numéro de facture</label>
                            <input type="text" name="search_number" id="search_number" class="form-control"
                                value="{{ request('search_number') }}" placeholder="Rechercher par numéro...">
                        </div>
                        <div class="col-md-3">
                            <label for="search_contact" class="small text-muted">
                                {{ $invoiceType === 'Clients' ? 'Client' : 'Fournisseur' }}
                            </label>
                            <input type="text" name="search_contact" id="search_contact" class="form-control"
                                value="{{ request('search_contact') }}"
                                placeholder="Nom {{ strtolower($invoiceType === 'Clients' ? 'du client' : 'du fournisseur') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="small text-muted">Statut</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Tous</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillon
                                </option>
                                <option value="validated" {{ request('status') === 'validated' ? 'selected' : '' }}>Validée
                                </option>
                                <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partielle
                                </option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Payée</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulée
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                            <a href="{{ route('invoices.index', $type) }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- =========================
                                                         SECTION 2 : LISTE DES FACTURES
                                                    ========================= -->
    <section id="invoice-list">
        <div class="card shadow border-left-primary">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-list-ul"></i> Liste des factures {{ strtolower($invoiceType) }}
                </h6>
            </div>
            <div class="card-body">
                @if ($invoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="thead-light text-uppercase text-secondary small">
                                <tr>
                                    <th>#</th>
                                    <th>Numéro</th>
                                    <th>{{ $invoiceType === 'Clients' ? 'Client' : 'Fournisseur' }}</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Total (FCFA)</th>
                                    <th>Balance (FCFA)</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>{{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}
                                        </td>
                                        <td>{{ $invoice->invoice_number ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route("$type.show", $invoice->contact->id) }}">
                                                {{ $invoice->contact->fullname ?? '-' }}
                                            </a>
                                        </td>
                                        <td>{{ $invoice->invoice_date ? Carbon::parse($invoice->invoice_date)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            @php
                                                $statusColor =
                                                    [
                                                        'draft' => 'secondary',
                                                        'validated' => 'info',
                                                        'partial' => 'warning',
                                                        'paid' => 'success',
                                                        'cancelled' => 'danger',
                                                    ][$invoice->status] ?? 'secondary';
                                            @endphp
                                            <span
                                                class="badge badge-{{ $statusColor }}">{{ ucfirst($invoice->status) }}</span>
                                        </td>
                                        <td>{{ number_format($invoice->total_invoice, 0, ',', ' ') }}</td>
                                        <td>{{ number_format($invoice->balance, 0, ',', ' ') }}</td>

                                        <td class="text-center">
                                            <!-- Bouton paiement facture -->
                                            <button type="button" class="btn btn-sm btn-primary" title="Payer"
                                                data-toggle="modal" data-target="#paymentModal{{ $invoice->id }}"
                                                @if (!in_array($invoice->status, ['validated', 'partial'])) disabled @endif>
                                                <i class="fas fa-money-bill"></i>
                                            </button>

                                            <!-- Modal paiement facture -->
                                            <div class="modal fade" id="paymentModal{{ $invoice->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="paymentModalLabel{{ $invoice->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content border-0 shadow-lg">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title"
                                                                id="paymentModalLabel{{ $invoice->id }}">
                                                                Paiement de la facture #{{ $invoice->invoice_number }}
                                                            </h5>
                                                            <button type="button" class="close text-white"
                                                                data-dismiss="modal" aria-label="Fermer">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <form action="{{ route('invoices.pay', [$type, $invoice->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="modal-body">
                                                                <input type="hidden" name="invoice_id"
                                                                    value="{{ $invoice->id }}">

                                                                <!-- Solde restant -->
                                                                <div class="alert alert-info text-center py-2 mb-3">
                                                                    <i class="fas fa-wallet"></i>
                                                                    Solde restant :
                                                                    <strong>{{ number_format($invoice->balance, 0, ',', ' ') }}
                                                                        FCFA</strong>
                                                                </div>

                                                                <!-- Montant payé -->
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span
                                                                            class="input-group-text bg-primary text-white">
                                                                            <i class="fas fa-dollar-sign"></i>
                                                                        </span>
                                                                    </div>
                                                                    <input type="number" class="form-control"
                                                                        id="amount_paid_{{ $invoice->id }}"
                                                                        name="amount_paid" placeholder="Montant payé"
                                                                        min="1" max="{{ $invoice->balance }}"
                                                                        required>
                                                                </div>

                                                                <!-- Type de paiement -->
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span
                                                                            class="input-group-text bg-primary text-white">
                                                                            <i class="fas fa-credit-card"></i>
                                                                        </span>
                                                                    </div>
                                                                    <input type="text" class="form-control"
                                                                        id="payment_type_{{ $invoice->id }}"
                                                                        name="payment_type"
                                                                        placeholder="Type de paiement (Ex : Espèces, Virement)"
                                                                        required>
                                                                </div>

                                                                <!-- Date du paiement -->
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span
                                                                            class="input-group-text bg-primary text-white">
                                                                            <i class="fas fa-calendar-alt"></i>
                                                                        </span>
                                                                    </div>
                                                                    <input type="date" class="form-control"
                                                                        id="payment_date_{{ $invoice->id }}"
                                                                        name="payment_date" value="{{ date('Y-m-d') }}"
                                                                        required>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-primary">Confirmer
                                                                    le paiement</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Bouton valider -->
                                            <form action="{{ route('invoices.validate', [$type, $invoice->id]) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Confirmez-vous la validation de cette facture ?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success" title="valider"
                                                    @if ($invoice->status != 'draft') disabled @endif>
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>

                                            <a href="{{ route('invoices.show', [$type, $invoice->id]) }}"
                                                class="btn btn-sm btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if ($invoice->status === 'draft')
                                                <a href="{{ route('invoices.edit', [$type, $invoice->id]) }}"
                                                    class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('invoices.destroy', [$type, $invoice->id]) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Confirmer la suppression de cette facture ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        title="Supprimer">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">
                            Affichage de {{ $invoices->firstItem() }} à {{ $invoices->lastItem() }} sur
                            {{ $invoices->total() }} factures
                        </small>
                        <div>
                            {{ $invoices->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Aucune facture {{ strtolower($invoiceType) }} trouvée.
                        <br>
                        <small>Essayez de modifier vos filtres ou créez-en une nouvelle.</small>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Modal ajout / édition d'une facture -->
    <div class="modal fade" id="addInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="addInvoiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content border-0 shadow-lg">
                @include('back.invoices._form', [
                    'route' => route('invoices.store', $type),
                    'method' => 'POST',
                    'invoice' => new \App\Models\Invoice(),
                    'products' => $products,
                    'contacts' => $contacts,
                    'warehouses' => $warehouses,
                ])
            </div>
        </div>
    </div>

    <!-- Modal ajout d'un entrepôt -->
    <div class="modal fade" id="addWarehouseModal" tabindex="-1" role="dialog"
        aria-labelledby="addWarehouseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                @include('back.warehouses._form', [
                    'route' => route('warehouses.store'),
                    'method' => 'POST',
                    'warehouse' => new \App\Models\Warehouse(),
                ])
            </div>
        </div>
    </div>

    <!-- Modal ajout -->
    <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                @include('back.contacts._form', [
                    'route' => route("$type.store"),
                    'method' => 'POST',
                    'contact' => new \App\Models\Contact(),
                    'type' => $type,
                ])
            </div>
        </div>
    </div>

    <!-- Modal ajout d'un produit -->
    <div class="modal fade" id="addproductModal" tabindex="-1" role="dialog" aria-labelledby="addproductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                @include('back.products._form', [
                    'route' => route('products.store'),
                    'method' => 'POST',
                    'product' => new \App\Models\Product(),
                    'categories' => \App\Models\Category::all(),
                    'units' => \App\Models\Units::all(),
                ])
            </div>
        </div>
    </div>

@endsection
