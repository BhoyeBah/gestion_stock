@extends('back.layouts.admin')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <style>
        /* En-tête de page */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .page-header h1 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1.75rem;
        }

        .page-header .btn {
            transition: all 0.3s ease;
        }

        .page-header .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Cartes statistiques */
        .stats-card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            overflow: hidden;
            background: #fff;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stats-card .card-body {
            padding: 1.5rem;
        }

        .stats-card .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stats-card.border-left-secondary .stats-icon {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .stats-card.border-left-info .stats-icon {
            background: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }

        .stats-card.border-left-warning .stats-icon {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .stats-card.border-left-success .stats-icon {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .stats-card.border-left-danger .stats-icon {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .stats-card.border-left-primary .stats-icon {
            background: rgba(78, 115, 223, 0.1);
            color: #4e73df;
        }

        /* Section recherche */
        .search-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .search-section .card-header {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            border: none;
            padding: 1.25rem 1.5rem;
        }

        .search-section .form-control,
        .search-section .form-control:focus {
            border-radius: 8px;
            border: 1px solid #e3e6f0;
        }

        .search-section .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
        }

        .search-section label {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 0.5rem;
        }

        /* Liste des factures */
        .invoice-list-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .invoice-list-section .card-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
            padding: 1.25rem 1.5rem;
        }

        .invoice-table {
            margin-bottom: 0;
        }

        .invoice-table thead th {
            background: #f8f9fc;
            color: #5a5c69;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border: none;
            padding: 1rem 0.75rem;
            white-space: nowrap;
        }

        .invoice-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #e3e6f0;
        }

        .invoice-table tbody tr:hover {
            background: #f8f9fc;
            transform: scale(1.01);
        }

        .invoice-table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        /* Badges de statut */
        .badge {
            padding: 0.5rem 0.875rem;
            font-weight: 600;
            font-size: 0.75rem;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Boutons d'action */
        .action-buttons .btn {
            margin: 0 0.125rem;
            transition: all 0.2s ease;
            border-radius: 6px;
        }

        .action-buttons .btn:hover {
            transform: scale(1.1);
        }

        /* Modal amélioré */
        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            border-radius: 15px 15px 0 0;
            border: none;
            padding: 1.5rem;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            border: none;
            padding: 1.5rem;
            background: #f8f9fc;
        }

        /* Alertes personnalisées */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 1.25rem;
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
        }

        /* Pagination */
        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            border-radius: 6px;
            margin: 0 0.125rem;
            border: none;
            color: #4e73df;
        }

        .page-link:hover {
            background: #4e73df;
            color: #fff;
        }

        .page-item.active .page-link {
            background: #4e73df;
            border-color: #4e73df;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stats-card {
            animation: fadeInUp 0.5s ease-out;
        }

        .stats-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stats-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stats-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stats-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .stats-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .stats-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        /* Input groups */
        .input-group-text {
            border-radius: 8px 0 0 8px;
            border: 1px solid #e3e6f0;
        }

        .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }

        /* Contact link */
        .contact-link {
            color: #4e73df;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .contact-link:hover {
            color: #224abe;
            text-decoration: underline;
        }
    </style>

    <!-- En-tête de page -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1>
                <i class="fas fa-file-invoice mr-2"></i> Factures {{ $invoiceType }}
            </h1>

            <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0">
                <button type="button" class="btn btn-light m-1" data-toggle="modal" data-target="#addContactModal">
                    <i class="fas fa-user-plus mr-1"></i>
                    Nouveau {{ $type === 'clients' ? 'client' : 'fournisseur' }}
                </button>

                <button type="button" class="btn btn-light m-1" data-toggle="modal" data-target="#addWarehouseModal">
                    <i class="fas fa-warehouse mr-1"></i> Nouvel entrepôt
                </button>

                <button type="button" class="btn btn-light m-1" data-toggle="modal" data-target="#addproductModal">
                    <i class="fas fa-box mr-1"></i> Nouveau produit
                </button>

                <button type="button" class="btn btn-warning m-1" data-toggle="modal" data-target="#addInvoiceModal">
                    <i class="fas fa-plus-circle mr-1"></i>
                    <strong>Nouvelle facture</strong>
                </button>
            </div>
        </div>
    </div>

    <!-- Cartes statistiques -->
    <div class="row mb-4">
        @php
            $statuses = [
                'draft' => ['label' => 'Brouillon', 'color' => 'secondary', 'icon' => 'fa-file'],
                'validated' => ['label' => 'Validée', 'color' => 'info', 'icon' => 'fa-check-circle'],
                'partial' => ['label' => 'Partielle', 'color' => 'warning', 'icon' => 'fa-clock'],
                'paid' => ['label' => 'Payée', 'color' => 'success', 'icon' => 'fa-check-double'],
                'cancelled' => ['label' => 'Annulée', 'color' => 'danger', 'icon' => 'fa-times-circle'],
            ];
        @endphp

        @foreach ($statuses as $key => $status)
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card stats-card border-left-{{ $status['color'] }} shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-{{ $status['color'] }} text-uppercase mb-2">
                                    {{ $status['label'] }}
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-gray-800">
                                    {{ $allInvoices->where('status', $key)->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="stats-icon">
                                    <i class="fas {{ $status['icon'] }}"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Total général -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card stats-card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">
                                Total
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                {{ $allInvoices->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="stats-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section recherche -->
    <div class="search-section">
        <div class="card-header text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-filter mr-2"></i> Recherche et filtres
            </h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('invoices.index', $type) }}">
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <label for="search_number">Numéro de facture</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                            </div>
                            <input type="text" name="search_number" id="search_number" class="form-control"
                                value="{{ request('search_number') }}" placeholder="Ex: FAC-2024-001">
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="search_contact">
                            {{ $invoiceType === 'Clients' ? 'Client' : 'Fournisseur' }}
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="search_contact" id="search_contact" class="form-control"
                                value="{{ request('search_contact') }}"
                                placeholder="Nom {{ strtolower($invoiceType === 'Clients' ? 'du client' : 'du fournisseur') }}">
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="status">Statut</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tags"></i></span>
                            </div>
                            <select name="status" id="status" class="form-control">
                                <option value="">Tous les statuts</option>
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
                    </div>

                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2 flex-fill">
                            <i class="fas fa-search mr-1"></i> Rechercher
                        </button>
                        <a href="{{ route('invoices.index', $type) }}" class="btn btn-secondary flex-fill">
                            <i class="fas fa-redo mr-1"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des factures -->
    <div class="invoice-list-section">
        <div class="card-header text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-list-ul mr-2"></i> Liste des factures {{ strtolower($invoiceType) }}
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($invoices->count() > 0)
                <div class="table-responsive">
                    <table class="table invoice-table">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Numéro</th>
                                <th>{{ $invoiceType === 'Clients' ? 'Client' : 'Fournisseur' }}</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th class="text-right">Total</th>
                                <th class="text-right">Balance</th>
                                <th class="text-center" width="250">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td class="font-weight-bold text-muted">
                                        {{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}
                                    </td>
                                    <td>
                                        <span class="font-weight-bold text-primary">
                                            {{ $invoice->invoice_number ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route("$type.show", $invoice->contact->id) }}" class="contact-link">
                                            <i class="fas fa-user-circle mr-1"></i>
                                            {{ $invoice->contact->fullname ?? '-' }}
                                        </a>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt text-muted mr-1"></i>
                                        {{ $invoice->invoice_date ? Carbon::parse($invoice->invoice_date)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'draft' => ['color' => 'secondary', 'icon' => 'fa-file'],
                                                'validated' => ['color' => 'info', 'icon' => 'fa-check-circle'],
                                                'partial' => ['color' => 'warning', 'icon' => 'fa-clock'],
                                                'paid' => ['color' => 'success', 'icon' => 'fa-check-double'],
                                                'cancelled' => ['color' => 'danger', 'icon' => 'fa-times-circle'],
                                            ];
                                            $config = $statusConfig[$invoice->status] ?? [
                                                'color' => 'secondary',
                                                'icon' => 'fa-file',
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $config['color'] }}">
                                            <i class="fas {{ $config['icon'] }} mr-1"></i>
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        {{ number_format($invoice->total_invoice, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="text-right">
                                        <span class="badge badge-{{ $invoice->balance > 0 ? 'warning' : 'success' }}">
                                            {{ number_format($invoice->balance, 0, ',', ' ') }} FCFA
                                        </span>
                                    </td>
                                    <td class="text-center action-buttons">
                                        <!-- ✅ Bouton Payer (affiché uniquement si la facture est validée ou partiellement payée) -->
                                        @if (in_array($invoice->status, ['validated', 'partial']))
                                            <button type="button" class="btn btn-sm btn-primary" title="Payer"
                                                data-toggle="modal" data-target="#paymentModal{{ $invoice->id }}">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </button>
                                        @endif

                                        <!-- ✅ Bouton Voir -->

                                        <a href="{{ route('invoices.show', [$type, $invoice->id]) }}"
                                            class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- ✅ Bouton Imprimer (toujours visible) -->
                                        <!-- Bouton Imprimer -->
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#printChoiceModal{{ $invoice->id }}">
                                            <i class="fas fa-print"></i>
                                        </button>

                                        <!-- ✅ Actions spécifiques au statut "draft" -->
                                        @if ($invoice->status === 'draft')
                                            <!-- Bouton Valider -->
                                            <form action="{{ route('invoices.validate', [$type, $invoice->id]) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Confirmez-vous la validation de cette facture ?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success" title="Valider">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>

                                            <!-- Bouton Modifier -->
                                            <a href="{{ route('invoices.edit', [$type, $invoice->id]) }}"
                                                class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Bouton Supprimer -->
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

                                <!-- Modal paiement -->
                                <div class="modal fade" id="paymentModal{{ $invoice->id }}" tabindex="-1"
                                    role="dialog">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-money-bill-wave mr-2"></i>
                                                    Paiement facture #{{ $invoice->invoice_number }}
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>

                                            <form action="{{ route('invoices.pay', [$type, $invoice->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                                                    <!-- Solde restant -->
                                                    <div class="alert alert-info text-center mb-4">
                                                        <i class="fas fa-wallet fa-2x mb-2"></i>
                                                        <div class="small">Solde restant</div>
                                                        <h4 class="mb-0 font-weight-bold">
                                                            {{ number_format($invoice->balance, 0, ',', ' ') }} FCFA
                                                        </h4>
                                                    </div>

                                                    <!-- Montant payé -->
                                                    <div class="form-group">
                                                        <label for="amount_paid_{{ $invoice->id }}">
                                                            <i class="fas fa-dollar-sign text-primary mr-1"></i>
                                                            Montant payé <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="number" class="form-control form-control-lg"
                                                            id="amount_paid_{{ $invoice->id }}" name="amount_paid"
                                                            placeholder="Entrez le montant" min="1"
                                                            max="{{ $invoice->balance }}" required>
                                                    </div>

                                                    <!-- Type de paiement -->
                                                    <div class="form-group">
                                                        <label for="payment_type_{{ $invoice->id }}">
                                                            <i class="fas fa-credit-card text-primary mr-1"></i>
                                                            Type de paiement <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" class="form-control"
                                                            id="payment_type_{{ $invoice->id }}" name="payment_type"
                                                            placeholder="Ex : Espèces, Virement, Chèque" required>
                                                    </div>

                                                    <!-- Date du paiement -->
                                                    <div class="form-group mb-0">
                                                        <label for="payment_date_{{ $invoice->id }}">
                                                            <i class="fas fa-calendar-alt text-primary mr-1"></i>
                                                            Date du paiement <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="date" class="form-control"
                                                            id="payment_date_{{ $invoice->id }}" name="payment_date"
                                                            value="{{ date('Y-m-d') }}" required>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">
                                                        <i class="fas fa-times mr-1"></i> Annuler
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-check mr-1"></i> Confirmer le paiement
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center p-4 border-top">
                    <div class="text-muted small">
                        <i class="fas fa-info-circle mr-1"></i>
                        Affichage de <strong>{{ $invoices->firstItem() }}</strong> à
                        <strong>{{ $invoices->lastItem() }}</strong> sur
                        <strong>{{ $invoices->total() }}</strong> factures
                    </div>
                    <div>
                        {{ $invoices->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-inbox fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Aucune facture {{ strtolower($invoiceType) }} trouvée</h5>
                    <p class="text-muted mb-4">
                        Essayez de modifier vos filtres ou créez-en une nouvelle
                    </p>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addInvoiceModal">
                        <i class="fas fa-plus-circle mr-2"></i> Créer une nouvelle facture
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal ajout facture -->
    <div class="modal fade" id="addInvoiceModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
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

    <!-- Modal ajout entrepôt -->
    <div class="modal fade" id="addWarehouseModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                @include('back.warehouses._form', [
                    'route' => route('warehouses.store'),
                    'method' => 'POST',
                    'warehouse' => new \App\Models\Warehouse(),
                ])
            </div>
        </div>
    </div>

    <!-- Modal ajout contact -->
    <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
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
    <div class="modal fade" id="addproductModal" tabindex="-1" role="dialog" aria-labelledby="addproductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
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

    @foreach ($invoices as $invoice)
<div class="modal fade" id="printChoiceModal{{ $invoice->id }}" tabindex="-1" role="dialog"
    aria-labelledby="printChoiceModalLabel{{ $invoice->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="printChoiceModalLabel{{ $invoice->id }}">
                    Choisir l'orientation d'impression
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-5">
                <div class="row justify-content-center">
                    <div class="col-6 col-md-5">
                        <a href="{{ route('invoices.print', [$type, $invoice->id]) }}?orientation=portrait"
                            target="_blank" class="btn btn-outline-primary btn-lg btn-block">
                            Portrait
                        </a>
                    </div>
                    <div class="col-6 col-md-5">
                        <a href="{{ route('invoices.print', [$type, $invoice->id]) }}?orientation=landscape"
                            target="_blank" class="btn btn-outline-secondary btn-lg btn-block">
                            Paysage
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>
@endforeach



@endsection
