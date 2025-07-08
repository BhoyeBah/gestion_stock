@extends('back.layouts.admin')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📦 Plans d’abonnement</h1>
    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau plan
    </a>
</div>

<!-- Table -->
<div class="card shadow border-left-primary">
    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Liste des plans disponibles</h6>
    </div>

    <div class="card-body">
        @if($plans->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle text-sm">
                    <thead class="thead-light text-uppercase text-secondary">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Prix</th>
                            <th>Durée</th>
                            <th>Utilisateurs</th>
                            <th>Stockage</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plans as $plan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $plan->name }}</strong>
                                    <br><small class="text-muted">{{ $plan->slug }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-info">
                                        {{ number_format($plan->price, 0, ',', ' ') }} FCFA
                                    </span>
                                </td>
                                <td>{{ $plan->duration_days }} jours</td>
                                <td>
                                    {{ $plan->max_users ? $plan->max_users : '∞' }}
                                    <i class="fas fa-user-friends text-muted"></i>
                                </td>
                                <td>
                                    {{ $plan->max_storage_mb ? $plan->max_storage_mb . ' Mo' : '∞' }}
                                    <i class="fas fa-hdd text-muted"></i>
                                </td>
                                <td>
                                    @if($plan->is_active)
                                        <span class="badge badge-success">Actif</span>
                                    @else
                                        <span class="badge badge-danger">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.plans.edit', $plan->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression de ce plan ?')">
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
        @else
            <div class="alert alert-info">
                Aucun plan disponible pour le moment. Créez-en un en cliquant sur le bouton ci-dessus.
            </div>
        @endif
    </div>
</div>
@endsection
