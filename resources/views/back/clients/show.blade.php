@extends('back.layouts.admin')

@section('content')
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-tie"></i> Détails du fournisseur
        </h1>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow border-left-primary mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Informations du fournisseur</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>Nom complet</th>
                        <td>{{ $supplier->full_name }}</td>
                    </tr>
                    <tr>
                        <th>Téléphone</th>
                        <td>{{ $supplier->phone_number }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $supplier->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Adresse</th>
                        <td>{{ $supplier->address }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($supplier->is_active)
                                <span class="badge badge-success">Activé</span>
                            @else
                                <span class="badge badge-danger">Désactivé</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Date de création</th>
                        <td>{{ $supplier->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Dernière mise à jour</th>
                        <td>{{ $supplier->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
