@extends('back.layouts.admin')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-invoice"></i> Factures {{ $invoiceType }}
        </h1>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addInvoiceModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouvelle facture
        </button>
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
                            <label for="search_contact"
                                class="small text-muted">{{ $invoiceType === 'Clients' ? 'Client' : 'Fournisseur' }}</label>
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
                                    <th>Entrepôt</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Total (FCFA)</th>
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
                                            <a
                                                href="{{ route("$type.show", $invoice->contact->id) }}">{{ $invoice->contact->fullname ?? '-' }}</a>
                                        </td>
                                        <td>{{ $invoice->warehouse->name ?? '-' }}</td>
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

                                        <td class="text-center">
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
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
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
@endsection
