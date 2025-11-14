@extends('back.layouts.admin')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-warehouse"></i> Entrepôt : <strong>{{ $warehouse->name }}</strong>
            </h1>
            <a href="{{ route('warehouses.index') }}" class="btn btn-outline-primary btn-sm shadow-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        {{-- Informations --}}
        <div class="card shadow border-left-primary mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">Informations générales</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>📌 Nom :</strong> {{ $warehouse->name }}</p>
                        <p><strong>📍 Adresse :</strong> {{ $warehouse->address ?? '-' }}</p>
                        <p><strong>👤 Responsable :</strong> <a href="{{ route("users.edit", $warehouse->manager?->id) }}">{{ $warehouse->manager?->name ?? '-' }}</a></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>📝 Description :</strong> {{ $warehouse->description ?? '-' }}</p>
                        <p><strong>⚙️ Statut :</strong>
                            @if ($warehouse->is_active)
                                <span class="badge badge-success">Activé</span>
                            @else
                                <span class="badge badge-danger">Désactivé</span>
                            @endif
                        </p>
                        <p><strong>📅 Créé le :</strong> {{ $warehouse->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lots --}}
        <div class="card shadow border-left-info mb-4">
            <div class="card-header bg-info text-white d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-boxes"></i> Lots disponibles</h6>
            </div>
            <div class="card-body">
                @if ($batches->count())
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-sm">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Produit</th>
                                    <th>Facture</th>
                                    <th>Prix (FCFA)</th>
                                    <th>Qté initiale</th>
                                    <th>Restante</th>
                                    <th>Expiration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($batches as $batch)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('products.show', $batch->product->id ?? '#') }}">
                                                {{ $batch->product->name ?? '-' }}
                                            </a>
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('invoices.show', [$batch->invoice->type . 's', $batch->invoice->id ?? '#']) }}">
                                                {{ $batch->invoice->invoice_number ?? '-' }}
                                            </a>
                                        </td>
                                        <td class="text-right">{{ number_format($batch->unit_price, 0, ',', ' ') }}</td>
                                        <td class="text-center">{{ $batch->quantity }}</td>
                                        <td class="text-center">{{ $batch->remaining }}</td>
                                        <td class="text-center">
                                            @if ($batch->expiration_date)
                                                {{ $batch->expiration_date->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $batches->links() }}
                    </div>
                @else
                    <div class="alert alert-light text-center">
                        <i class="fas fa-info-circle"></i> Aucun lot disponible pour cet entrepôt.
                    </div>
                @endif
            </div>
        </div>

        {{-- Mouvements --}}
        <div class="card shadow border-left-warning mb-4">
            <div class="card-header bg-warning text-white">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-exchange-alt"></i> Mouvements de stock</h6>
            </div>
            <div class="card-body">
                @if ($movements->count())
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-sm">
                            <thead class="text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Produit</th>
                                    <th>Type</th>
                                    <th>Quantité</th>
                                    <th>Facture</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movements as $movement)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('products.show', $movement->product->id ?? '#') }}">
                                                {{ $movement->product->name ?? '-' }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{ $movement->reason }}
                                        </td>
                                        <td class="text-center">{{ $movement->quantity }}</td>
                                        <td>
                                            <a
                                                href="#">
                                                {{ $movement->invoice?->invoice_number ?? '-' }}
                                            </a>
                                        </td>
                                        <td class="text-center">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $movements->links() }}
                    </div>
                @else
                    <div class="alert alert-light text-center">
                        <i class="fas fa-info-circle"></i> Aucun mouvement enregistré.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
