@extends('back.layouts.admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Souscriptions</h1>
    <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Nouvelle souscription
    </a>
</div>

<div class="card shadow mb-4 border-left-info">
    <div class="card-header py-3 bg-info text-white">
        <h6 class="m-0 font-weight-bold">Liste des souscriptions</h6>
    </div>

    <div class="card-body">
        
        

        @if($subscriptions->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm align-middle">
                    <thead class="thead-light text-uppercase">
                        <tr>
                            <th>#</th>
                            <th>Entreprise</th>
                            <th>Plan</th>
                            <th>Montant</th>
                            <th>Paiement</th>
                            <th>Début - Fin</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptions as $subscription)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $subscription->tenant->name }}</strong></td>
                                <td>
                                    <span class="badge badge-primary">
                                        {{ $subscription->plan->name }}
                                    </span>
                                </td>
                                <td>{{ number_format($subscription->amount_paid, 0, ',', ' ') }} FCFA</td>
                                <td>{{ $subscription->payment_method ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-light">
                                        {{ $subscription->starts_at->format('d/m/Y') }}
                                        →
                                        {{ $subscription->ends_at->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if ($subscription->ends_at < now())
                                        <span class="badge badge-secondary">Expirée</span>
                                    @elseif ($subscription->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                        {{-- Voir & imprimer --}}
                                        <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="btn btn-sm btn-info" title="Voir et imprimer">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($subscription->ends_at >= now())
                                            {{-- Modifier --}}
                                            <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- Activer/Désactiver --}}
                                            <form action="{{ route('admin.subscriptions.toggle', $subscription) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $subscription->is_active ? 'btn-secondary' : 'btn-success' }}" title="{{ $subscription->is_active ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas fa-toggle-{{ $subscription->is_active ? 'off' : 'on' }}"></i>
                                                </button>
                                            </form>

                                            {{-- Supprimer --}}
                                            <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">Verrouillé</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $subscriptions->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Aucune souscription enregistrée pour le moment.
            </div>
        @endif
    </div>
</div>
@endsection
