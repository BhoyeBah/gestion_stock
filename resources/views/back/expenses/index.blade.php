@extends('back.layouts.admin')

@section('content')

    <!-- En-tête de la page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-money-bill-wave"></i> Dépenses internes
        </h1>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addExpenseModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouvelle dépense
        </button>
    </div>

    <!-- Cartes statistiques -->
    <div class="row mb-4">

        <!-- Total général -->
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total des dépenses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($expenses->sum('amount'), 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dépenses aujourd'hui -->
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Aujourd'hui</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($expenses->where('expense_date', '>=', now()->startOfDay())->sum('amount'), 0, ',', ' ') }}
                                FCFA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dépenses cette semaine -->
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Cette semaine</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($expenses->where('expense_date', '>=', now()->startOfWeek())->sum('amount'), 0, ',', ' ') }}
                                FCFA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dépenses ce mois -->
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Ce mois</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($expenses->where('expense_date', '>=', now()->startOfMonth())->sum('amount'), 0, ',', ' ') }}
                                FCFA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- Carte principale -->
    <div class="card shadow border-left-primary">
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-list-ul"></i> Liste des dépenses enregistrées</h6>
        </div>

        <div class="card-body">
            @if ($expenses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="thead-light text-uppercase text-secondary small">
                            <tr>
                                <th>#</th>
                                <th>Motif</th>
                                <th>Montant (FCFA)</th>
                                <th>Date dépense</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $expense)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong class="text-dark">{{ $expense->reason }}</strong></td>
                                    <td class="text-success font-weight-bold">
                                        {{ number_format($expense->amount, 0, ',', ' ') }}
                                    </td>
                                    <td>{{ $expense->expense_date ? $expense->expense_date->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="text-center">

                                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Confirmer la suppression de cette dépense ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $expenses->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Aucune dépense enregistrée pour le moment.
                    <br>
                    <small>Ajoutez une nouvelle dépense à l’aide du bouton ci-dessus.</small>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal : Ajout d'une dépense -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog" aria-labelledby="addExpenseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                @include('back.expenses._form', [
                    'route' => route('expenses.store'),
                    'method' => 'POST',
                    'expense' => new \App\Models\Expense(),
                ])
            </div>
        </div>
    </div>

@endsection
