@extends('back.layouts.admin')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line"></i> Rapports
        </h1>
    </div>

    {{-- Filtres --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="form-row align-items-end">
                <div class="form-group col-md-3">
                    <label>Date du</label>
                    <input type="date" name="date_from" class="form-control">
                </div>

                <div class="form-group col-md-3">
                    <label>Date au</label>
                    <input type="date" name="date_to" class="form-control">
                </div>

                <div class="form-group col-md-3">
                    <label>Type</label>
                    <select class="form-control" name="type">
                        <option value="">Tous</option>
                        <option value="client">Client</option>
                        <option value="supplier">Fournisseur</option>
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
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-file-invoice fa-2x text-primary"></i>
                    </div>
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Factures</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">1 200 000 FCFA</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
                    </div>
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Payé</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">800 000 FCFA</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                    </div>
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En Attente</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">250 000 FCFA</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                    <div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Annulées</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">150 000 FCFA</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Graphique --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Évolution des ventes</h6>
        </div>
        <div class="card-body" style="position: relative; height:300px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Détail des factures</h6>
            <div>
                <button class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i> Export PDF</button>
                <button class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Export Excel</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>N° Facture</th>
                        <th>Client / Fournisseur</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>FAC-2025-0001</td>
                        <td>Jean Dupont</td>
                        <td>2025-11-05</td>
                        <td>150 000 FCFA</td>
                        <td><span class="badge badge-success">Payé</span></td>
                    </tr>
                    <tr>
                        <td>FAC-2025-0002</td>
                        <td>Marie Claire</td>
                        <td>2025-11-04</td>
                        <td>200 000 FCFA</td>
                        <td><span class="badge badge-warning">Partiel</span></td>
                    </tr>
                    <tr>
                        <td>FAC-2025-0003</td>
                        <td>Abdoulaye</td>
                        <td>2025-11-03</td>
                        <td>300 000 FCFA</td>
                        <td><span class="badge badge-danger">Annulé</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["01 Nov", "02 Nov", "03 Nov", "04 Nov", "05 Nov"],
                    datasets: [{
                        label: "Ventes (FCFA)",
                        data: [120000, 150000, 170000, 140000, 200000],
                        fill: false,
                        borderColor: 'rgba(78,115,223,1)',
                        backgroundColor: 'rgba(78,115,223,0.05)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(78,115,223,1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(78,115,223,1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function(value) {
                                    return value.toLocaleString() + " FCFA";
                                }
                            }
                        }]
                    }
                }
            });
        });
    </script>
@endsection
