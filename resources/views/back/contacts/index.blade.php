@extends('back.layouts.admin')

@section('title', 'Liste des ' . $contactType)

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0 text-gray-800">
            <i class="fas fa-users"></i> {{ $contactType }}
        </h4>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addContactModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau {{ $type === 'clients' ? 'client' : 'fournisseur' }}
        </button>
    </div>

    <!-- Liste des contacts -->
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-list-ul"></i> Liste des {{ $contactType }}</h6>
        </div>
        <div class="card-body">
            @if ($contacts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="thead-light text-uppercase text-secondary small">
                            <tr>
                                <th>#</th>
                                <th>Nom complet</th>
                                <th>Téléphone</th>
                                <th>Adresse</th>
                                <th>Solde</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contacts as $contact)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $contact->fullname }}</strong></td>
                                    <td>{{ $contact->phone_number }}</td>
                                    <td>{{ $contact->address ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $contact->balance_total > 0 ? 'badge-danger' : 'badge-success' }}">
                                            {{ number_format($contact->balance_total ?? 0, 0, ',', ' ') }} CFA
                                        </span>
                                    </td>
                                    <td>
                                        @if ($contact->is_active)
                                            <span class="badge badge-success">Activé</span>
                                        @else
                                            <span class="badge badge-danger">Désactivé</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <!-- Activer / Désactiver -->
                                        <form action="{{ route("$type.toggle", $contact->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Voulez-vous {{ $contact->is_active ? 'désactiver' : 'activer' }} ce {{ $type === 'clients' ? 'client' : 'fournisseur' }} ?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $contact->is_active ? 'btn-success' : 'btn-danger' }}">
                                                <i class="fas fa-toggle-{{ $contact->is_active ? 'off' : 'on' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Voir -->
                                        <a href="{{ route("$type.show", $contact->id) }}" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Modifier -->
                                        <a href="{{ route("$type.edit", $contact->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Supprimer -->
                                        <form action="{{ route("$type.destroy", $contact->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Confirmer la suppression de ce {{ $type === 'clients' ? 'client' : 'fournisseur' }} ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
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
                    <i class="fas fa-info-circle"></i> Aucun {{ $type === 'clients' ? 'client' : 'fournisseur' }} disponible.
                    <br>
                    <small>Créez-en un en cliquant sur le bouton ci-dessus.</small>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal ajout -->
    <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                @include('back.contacts._form', [
                    'route' => route("$type.store"),
                    'method' => 'POST',
                    'contact' => new \App\Models\Contact(),
                    'type' => $type,
                ])
            </div>
        </div>
    </div>

</div>
@endsection
