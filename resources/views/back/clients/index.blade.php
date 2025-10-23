@extends('back.layouts.admin')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-users"></i> Clients
    </h1>
    <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addclientModal">
        <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau client
    </button>
</div>

<div class="card shadow border-left-primary">
    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-list-ul"></i> Liste des clients
        </h6>
    </div>

    <div class="card-body">
        @if ($clients->count() > 0)
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
                    @foreach ($clients as $client)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $client->full_name }}</strong></td>
                        <td>{{ $client->phone_number }}</td>
                        <td>{{ $client->email ?? '-' }}</td>
                        <td>{{ $client->address }}</td>
                        <td>
                            @if ($client->is_active)
                                <span class="badge badge-success">Activé</span>
                            @else
                                <span class="badge badge-danger">Désactivé</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <!-- Activer / Désactiver -->
                            <form action="{{ route('clients.toggle', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous {{ $client->is_active ? 'désactiver' : 'activer' }} ce client ?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $client->is_active ? 'btn-success' : 'btn-danger' }}" title="{{ $client->is_active ? 'Désactiver' : 'Activer' }}">
                                    <i class="fas fa-toggle-{{ $client->is_active ? 'off' : 'on' }}"></i>
                                </button>
                            </form>

                            <!-- Modifier -->
                            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Voir -->
                            <a href="{{ route('clients.show', $client->id) }}" class="btn btn-sm btn-info" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Supprimer -->
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression de ce client ?')">
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
            <i class="fas fa-info-circle"></i> Aucun client disponible pour le moment.
            <br>
            <small>Créez-en un en cliquant sur le bouton « Nouveau client » ci-dessus.</small>
        </div>
        @endif
    </div>
</div>

<!-- Modal ajout d'un client -->
<div class="modal fade" id="addclientModal" tabindex="-1" role="dialog" aria-labelledby="addclientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            @include('back.clients._form', [
                'route' => route('clients.store'),
                'method' => 'POST',
                'client' => new \App\Models\Client(),
            ])
        </div>
    </div>
</div>

@endsection
