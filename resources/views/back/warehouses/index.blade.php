@extends('back.layouts.admin')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-warehouse"></i> Entrepôts
        </h1>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addWarehouseModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouvel entrepôt
        </button>
    </div>

    <div class="card shadow border-left-primary">
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-list-ul"></i> Liste des entrepôts disponibles
            </h6>
        </div>

        <div class="card-body">
            @if ($warehouses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="thead-light text-uppercase text-secondary small">
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Adresse</th>
                                <th>Responsable</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warehouses as $warehouse)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $warehouse->name }}</strong></td>
                                    <td>{{ $warehouse->address ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('users.edit', $warehouse->manager?->id ?? '#') }}">
                                            {{ $warehouse->manager?->name ?? '-' }}
                                        </a>
                                    </td>
                                    <td>{{ Str::limit($warehouse->description, 50) ?? '-' }}</td>
                                    <td>
                                        @if ($warehouse->is_active)
                                            <span class="badge badge-success">Activé</span>
                                        @else
                                            <span class="badge badge-danger">Désactivé</span>
                                        @endif
                                    </td>
                                    <td class="text-center">

                                        <a href="{{ route('warehouses.exchange', $warehouse->id) }}" class="btn btn-sm btn-info"
                                            title="Transférer">
                                            <i class="fas fa-exchange-alt"></i>
                                        </a>


                                        <!-- Activer / Désactiver -->
                                        <form action="{{ route('warehouses.toggle', $warehouse->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Voulez-vous {{ $warehouse->is_active ? 'désactiver' : 'activer' }} cet entrepôt ?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $warehouse->is_active ? 'btn-success' : 'btn-danger' }}"
                                                title="{{ $warehouse->is_active ? 'Désactiver' : 'Activer' }}">
                                                <i class="fas fa-toggle-{{ $warehouse->is_active ? 'off' : 'on' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Modifier -->
                                        <a href="{{ route('warehouses.edit', $warehouse->id) }}"
                                            class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Voir -->
                                        <a href="{{ route('warehouses.show', $warehouse->id) }}"
                                            class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Supprimer -->
                                        <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Confirmer la suppression de cet entrepôt ?')">
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
                    <i class="fas fa-info-circle"></i> Aucun entrepôt disponible pour le moment.
                    <br>
                    <small>Créez-en un en cliquant sur le bouton ci-dessus.</small>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal ajout d'un entrepôt -->
    <div class="modal fade" id="addWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="addWarehouseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                @include('back.warehouses._form', [
                    'route' => route('warehouses.store'),
                    'method' => 'POST',
                    'warehouse' => new \App\Models\Warehouse(),
                ])
            </div>
        </div>
    </div>

@endsection
