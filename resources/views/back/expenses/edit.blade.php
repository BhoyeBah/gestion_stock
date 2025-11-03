@extends('back.layouts.admin')

@section('content')

<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit"></i> Modifier la dépense
        </h1>
        <a href="{{ route('expenses.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow border-left-primary">
        <div class="card-header bg-primary text-white py-3">
            <h6 class="m-0 font-weight-bold">Formulaire de modification de la dépense</h6>
        </div>

        <div class="card-body">
            @include('back.expenses._form', [
                'method' => 'PUT',
                'route' => route('expenses.update', $expense),
                'expense' => $expense
            ])
        </div>
    </div>

</div>
@endsection
