@extends('back.layouts.admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line"></i> Rapports des Factures
        </h1>
    </div>

    {{-- Filtres --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="form-row align-items-end">
                <div class="form-group col-md-3">
                    <label>Date du</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="form-group col-md-3">
                    <label>Date au</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="form-group col-md-3">
                    <label>Type</label>
                    <select class="form-control" name="type">
                        <option value="">Tous</option>
                        <option value="client" {{ request('type') == 'client' ? 'selected' : '' }}>Client</option>
                        <option value="supplier" {{ request('type') == 'supplier' ? 'selected' : '' }}>Fournisseur</option>
                    </select>
                </div>
                <div class="form-group col-md-3 text-right">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search"></i> Générer
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Statistiques --}}
    <div class="row">
        @php
            $format = fn($v) => number_format($v ?? 0, 0, ',', ' ') . ' FCFA';
        @endphp

        @foreach ([
            'Total Factures' => ['value' => $stats->total_factures, 'icon' => 'fa-file-invoice', 'color' => 'primary'],
            'Total Payé' => ['value' => $stats->total_paye, 'icon' => 'fa-dollar-sign', 'color' => 'success'],
            'En Attente' => ['value' => $stats->total_attente, 'icon' => 'fa-hourglass-half', 'color' => 'warning'],
            'Annulées' => ['value' => $stats->total_annule, 'icon' => 'fa-times-circle', 'color' => 'danger'],
        ] as $title => $data)
            <div class="col-md-3 mb-4">
                <div class="card border-left-{{ $data['color'] }} shadow h-100 py-2">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas {{ $data['icon'] }} fa-2x text-{{ $data['color'] }} mr-3"></i>
                        <div>
                            <div class="text-xs font-weight-bold text-{{ $data['color'] }} text-uppercase mb-1">
                                {{ $title }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold">{{ $format($data['value']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Tableau --}}
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Détail des factures</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>N° Facture</th>
                        <th>Client / Fournisseur</th>
                        <th>Date</th>
                        <th>Balance</th>
                        <th>Total</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoicesList as $invoice)
                        @php
                            $contactRoute = $invoice->type === 'client' ? 'clients.show' : 'suppliers.show';
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('invoices.show', [$invoice->type . 's', $invoice->id]) }}">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>

                            <td>
                                <a href="{{ route($contactRoute, $invoice->contact->id) }}">
                                    {{ $invoice->contact->fullname ?? '-' }}
                                </a>
                            </td>

                            <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                            <td>{{ number_format($invoice->balance, 0, ',', ' ') }} FCFA</td>
                            <td>{{ number_format($invoice->total_invoice, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @php
                                    $statusLabel = match ($invoice->status) {
                                        'paid' => 'Payé',
                                        'partial' => 'Partiellement payé',
                                        'cancelled' => 'Annulée',
                                        default => ucfirst($invoice->status),
                                    };
                                    $statusColor = match ($invoice->status) {
                                        'paid' => 'success',
                                        'partial' => 'warning',
                                        'cancelled' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge badge-{{ $statusColor }}">{{ $statusLabel }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $invoicesList->links() }}
        </div>
    </div>

    {{-- Graphique Évolution des ventes --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Évolution des ventes</h6>
        </div>
        <div class="card-body" style="position: relative; height:300px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json($chartData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')));
        const values = @json($chartData->pluck('total'));

        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: "Ventes (FCFA)",
                    data: values,
                    fill: false,
                    borderColor: 'rgba(78,115,223,1)',
                    backgroundColor: 'rgba(78,115,223,0.2)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(78,115,223,1)',
                    pointRadius: 4,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
