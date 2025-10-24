@extends('back.layouts.admin')

@section('content')
    <div class="container">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit"></i> Modifier l’entrepôt
            </h1>
            <a href="{{ route('warehouses.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <div class="card shadow border-left-primary">
            <div class="card-header bg-primary text-white py-3">
                <h6 class="m-0 font-weight-bold">Formulaire de modification</h6>
            </div>

            <div class="card-body">
                @include('back.warehouses._form', [
                    'method' => 'PUT',
                    'route' => route('warehouses.update', $warehouse->id),
                    'warehouse' => $warehouse
                ])
            </div>
        </div>
    </div>
@endsection
