@extends('back.layouts.admin')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-invoice"></i> Factures Fournisseurs
        </h1>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addInvoiceModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouvelle facture
        </button>
    </div>

    {{-- Formulaire de filtre et pagination --}}
    <div class="d-flex justify-content-between mb-3">
        <form action="{{ route('invoice_suppliers.index') }}" method="GET" class="form-inline">
            <label for="status" class="mr-2 font-weight-bold">Filtrer par statut :</label>
            <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                <option value="">Tous</option>
                <option value="DRAFT" {{ request('status') === 'DRAFT' ? 'selected' : '' }}>Brouillon</option>
                <option value="PARTIALLY_PAID" {{ request('status') === 'PARTIALLY_PAID' ? 'selected' : '' }}>Partiellement
                    payé</option>
                <option value="PAID" {{ request('status') === 'PAID' ? 'selected' : '' }}>Payé</option>
                <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Annulé</option>
            </select>

            @if (request('status'))
                <a href="{{ route('invoice_suppliers.index') }}" class="btn btn-secondary ml-2">
                    <i class="fas fa-times"></i> Réinitialiser
                </a>
            @endif
        </form>

        <form action="{{ route('invoice_suppliers.index') }}" method="GET" class="form-inline">
            <label for="perPage" class="mr-2 font-weight-bold">Afficher :</label>
            <select name="perPage" id="perPage" class="form-control" onchange="this.form.submit()">
                @foreach ([5, 10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>
                        {{ $size }}</option>
                @endforeach
            </select>
            <span class="ml-2">factures par page</span>

            {{-- Conserver le filtre status lors du changement de perPage --}}
            <input type="hidden" name="status" value="{{ request('status') }}">
        </form>
    </div>

    <div class="card shadow border-left-primary">
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-list-ul"></i>
                Liste des factures
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
                                <th>Fournisseur</th>
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
                                    <td>{{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}</td>
                                    <td>{{ $invoice->invoice_number ?? '-' }}</td>
                                    <td>{{ $invoice->supplier->full_name ?? '-' }}</td>
                                    <td>{{ $invoice->warehouse->name ?? '-' }}</td>
                                    <td>{{ $invoice->invoice_date ? Carbon::parse($invoice->invoice_date)->format('d/m/Y') : '-' }}
                                    </td>
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
                                    <td>{{ $invoice->items->sum(fn($i) => $i->quantity * $i->purchase_price - ($i->discount ?? 0)) }}
                                    </td>
                                    <td class="text-center">

                                        <a href="{{ route('invoice_suppliers.show', $invoice->id) }}"
                                            class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Delete and edit invoices "status == "DRAFT" --}}
                                        @if ($invoice->status == 'DRAFT')

                                            <a href="{{ route('invoice_suppliers.edit', $invoice->id) }}"
                                                class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('invoice_suppliers.destroy', $invoice->id) }}"
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

                {{-- Pagination --}}
                <div class="d-flex justify-content-end">
                    {{ $invoices->withQueryString()->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Aucune facture disponible pour le moment.
                    <br>
                    <small>Créez-en une en cliquant sur le bouton « Nouvelle facture » ci-dessus.</small>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal ajout / édition d'une facture -->
    <div class="modal fade" id="addInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="addInvoiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content border-0 shadow-lg">
                @include('back.invoices._form', [
                    'route' => route('invoice_suppliers.store'),
                    'method' => 'POST',
                    'invoice' => new \App\Models\Invoice(),
                    'products' => $products,
                    'suppliers' => $suppliers,
                    'warehouses' => $warehouses,
                ])
            </div>
        </div>
    </div>
@endsection
