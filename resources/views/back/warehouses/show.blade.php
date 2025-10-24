@extends('back.layouts.admin')

@section('content')
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-warehouse"></i> Détails de l’entrepôt
        </h1>
        <a href="{{ route('warehouses.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    {{-- Informations de l’entrepôt --}}
    <div class="card shadow border-left-primary mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Informations de l’entrepôt</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>Nom</th>
                        <td>{{ $warehouse->name }}</td>
                    </tr>
                    <tr>
                        <th>Adresse</th>
                        <td>{{ $warehouse->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Responsable</th>
                        <td>{{ $warehouse->manager?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $warehouse->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($warehouse->is_active)
                                <span class="badge badge-success">Activé</span>
                            @else
                                <span class="badge badge-danger">Désactivé</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Date de création</th>
                        <td>{{ $warehouse->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Dernière mise à jour</th>
                        <td>{{ $warehouse->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Produits disponibles dans l’entrepôt --}}
    <div class="card shadow border-left-primary mb-4">
        <div class="card-header bg-info text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-boxes"></i> Produits disponibles
            </h6>
        </div>
        {{-- <div class="card-body">
            @if ($warehouse->products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="thead-light text-uppercase text-secondary small">
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Unité</th>
                                <th>Prix</th>
                                <th>Stock disponible</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warehouse->products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                    <td>{{ $product->unit->name ?? '-' }}</td>
                                    <td>{{ number_format($product->price, 0, ',', ' ') }} CFA</td>
                                    <td>{{ $product->pivot->quantity ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Aucun produit disponible pour cet entrepôt.
                </div>
            @endif
        </div> --}}
    </div>
</div>
@endsection
