@extends('back.layouts.admin')

@section('content')
<!-- En-tête -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">🏢 Entreprises</h1>
    <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary">
        <i class="fas fa-plus fa-sm text-white-50"></i> Nouvelle entreprise
    </a>
</div>

<!-- Table des tenants -->
<div class="card shadow border-left-primary">
    <div class="card-header bg-primary text-white py-3">
        <h6 class="m-0 font-weight-bold">Liste des entreprises</h6>
    </div>

    <div class="card-body">
        @if($tenants->count())
            <div class="table-responsive">
                <table class="table table-hover text-sm align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Logo</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th>Créée le</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenants as $tenant)
                            <tr>
                                <td>
                                    @if($tenant->logo)
                                        <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" class="img-thumbnail" width="50">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $tenant->name }}</strong><br>
                                    <small class="text-muted">{{ $tenant->slug }}</small>
                                </td>
                                <td>{{ $tenant->email ?? '—' }}</td>
                                <td>{{ $tenant->phone ?? '—' }}</td>
                                <td>
                                    @if($tenant->is_active)
                                        <span class="badge badge-success">Actif</span>
                                    @else
                                        <span class="badge badge-danger">Inactif</span>
                                    @endif
                                </td>
                                <td>{{ $tenant->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.tenants.edit', $tenant->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression de cette entreprise ?')">
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

            <div class="mt-3">
                {{ $tenants->links() }} <!-- pagination -->
            </div>
        @else
            <div class="alert alert-info">Aucune entreprise enregistrée pour le moment.</div>
        @endif
    </div>
</div>
@endsection
