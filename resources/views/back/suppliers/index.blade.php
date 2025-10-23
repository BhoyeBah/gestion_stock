@extends('back.layouts.admin')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-users"></i> Fournisseurs
    </h1>
    <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addSupplierModal">
        <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau fournisseur
    </button>
</div>

<div class="card shadow border-left-primary">
    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-list-ul"></i> Liste des fournisseurs disponibles
        </h6>
    </div>

    <div class="card-body">
        @if ($suppliers->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="thead-light text-uppercase text-secondary small">
                    <tr>
                        <th>#</th>
                        <th>Nom complet</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $supplier->full_name }}</strong></td>
                        <td>{{ $supplier->phone_number }}</td>
                        <td>{{ $supplier->email ?? '-' }}</td>
                        <td>{{ $supplier->address }}</td>
                        <td>
                            @if ($supplier->is_active)
                                <span class="badge badge-success">Activé</span>
                            @else
                                <span class="badge badge-danger">Désactivé</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <!-- Activer / Désactiver -->
                            <form action="{{ route('suppliers.toggle', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous {{ $supplier->is_active ? 'désactiver' : 'activer' }} ce fournisseur ?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $supplier->is_active ? 'btn-success' : 'btn-danger' }}" title="{{ $supplier->is_active ? 'Désactiver' : 'Activer' }}">
                                    <i class="fas fa-toggle-{{ $supplier->is_active ? 'off' : 'on' }}"></i>
                                </button>
                            </form>

                            <!-- Modifier -->
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Voir -->
                            <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-sm btn-info" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Supprimer -->
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression de ce fournisseur ?')">
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
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Aucun fournisseur disponible pour le moment.
            <br>
            <small>Créez-en un en cliquant sur le bouton ci-dessus.</small>
        </div>
        @endif
    </div>
</div>

<!-- Modal ajout d'un fournisseur -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            @include('back.suppliers._form', [
                'route' => route('suppliers.store'),
                'method' => 'POST',
                'supplier' => new \App\Models\Supplier(),
            ])
        </div>
    </div>
</div>

@endsection
