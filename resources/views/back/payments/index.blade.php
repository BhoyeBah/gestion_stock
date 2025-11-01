@extends('back.layouts.admin')

@php use Carbon\Carbon; @endphp

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-money-bill-wave"></i> Liste des paiements
        </h1>
    </div>

    <!-- ========================= FILTRES ========================= -->
    <section id="payment-filters">
        <div class="card shadow border-left-info mb-4">
            <div class="card-header bg-info text-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-search"></i> Recherche et filtres
                </h6>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('payments.index', $type) }}">
                    <div class="form-row">
                        <div class="col-md-4">
                            <label class="small text-muted">Numéro de facture</label>
                            <input type="text" name="invoice_number" class="form-control"
                                value="{{ request('invoice_number') }}" placeholder="N° facture">
                        </div>

                        <div class="col-md-4">
                            <label class="small text-muted">Fournisseur / Client</label>
                            <input type="text" name="tenant" class="form-control" value="{{ request('tenant') }}"
                                placeholder="Nom du contact">
                        </div>

                        <div class="col-md-4 text-right">
                            <button class="btn btn-primary">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                            <a href="{{ route('payments.index', $type) }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- ========================= LISTE DES PAIEMENTS ========================= -->
    <section id="payment-list">
        <div class="card shadow border-left-primary">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-list-ul"></i> Paiements enregistrés
                </h6>
            </div>

            <div class="card-body">
                @if ($payments->count())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light text-uppercase text-secondary small">
                                <tr>
                                    <th>#</th>
                                    <th>Facture</th>
                                    <th>Nom</th>
                                    <th>Type</th>
                                    <th>Montant payé</th>
                                    <th>Reste</th>
                                    <th>Date</th>
                                    <th>Méthode</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}
                                        </td>

                                        <td>
                                            <a href="{{ route('invoices.show', [$type, $payment->invoice_id]) }}">
                                                {{ $payment->invoice->invoice_number ?? '-' }}
                                            </a>
                                        </td>

                                        <td>
                                            <a href="{{ route("$type.show", $payment->contact_id) }}">
                                                {{ $payment->contact->fullname ?? '-' }}
                                            </a>
                                        </td>

                                        <!-- ✅ Type (Fournisseur / Client) -->
                                        <td>
                                            {{ $payment->contact ? ($payment->contact->type === 'supplier' ? 'Fournisseur' : 'Client') : '-' }}
                                        </td>

                                        <td>{{ number_format($payment->amount_paid, 0, ',', ' ') }}</td>
                                        <td>{{ number_format($payment->remaining_amount, 0, ',', ' ') }}</td>
                                        <td>{{ Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                        <td>{{ ucfirst($payment->payment_type) }}</td>

                                        <td>

                                            <a href="{{ route('payments.show', [$type, $payment->id]) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('payments.destroy', [$type, $payment->id]) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Confirmez-vous la suppression de ce paiement ?')">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-danger"
                                                    @if ($payment->amount_paid == 0) disabled @endif>
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>

                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">
                            Affichage de {{ $payments->firstItem() }} à {{ $payments->lastItem() }} sur
                            {{ $payments->total() }} paiements
                        </small>
                        <div>
                            {{ $payments->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Aucun paiement trouvé.
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
