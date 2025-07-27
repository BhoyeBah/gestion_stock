@extends('back.layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Détail de la souscription</h5>
            <div>
            
                <form action="{{ route('tenant.subscriptions.pdf', $subscription) }}" method="GET" class="d-inline">
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fas fa-file-pdf"></i> Télécharger PDF
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            {{-- Infos Plan --}}
            <h4 class="text-primary mb-3">{{ $subscription->plan->name }}</h4>
            <p class="mb-1">
                <strong>Prix :</strong> {{ number_format($subscription->plan->price, 0, ',', ' ') }} FCFA
            </p>
            <p class="mb-1">
                <strong>Durée :</strong> {{ $subscription->plan->duration_days }} jours
            </p>
            @if($subscription->plan->max_users)
                <p class="mb-1">
                    <strong>Utilisateurs max :</strong> {{ $subscription->plan->max_users }}
                </p>
            @endif
            @if($subscription->plan->max_storage_mb)
                <p class="mb-1">
                    <strong>Stockage max :</strong> {{ $subscription->plan->max_storage_mb }} Mo
                </p>
            @endif
            @if($subscription->plan->description)
                <p class="mb-3">
                    <strong>Description :</strong> {!! nl2br(e($subscription->plan->description)) !!}
                </p>
            @endif

            <hr>

            {{-- Infos Souscription --}}
            <h5>Détails de la souscription</h5>
            <p>
                <strong>Montant payé :</strong> {{ number_format($subscription->amount_paid, 0, ',', ' ') }} FCFA
            </p>
            <p>
                <strong>Méthode de paiement :</strong> {{ $subscription->payment_method ?? '-' }}
            </p>
            <p>
                <strong>Période :</strong> {{ $subscription->starts_at->format('d/m/Y') }} → {{ $subscription->ends_at->format('d/m/Y') }}
            </p>

            @php
                $status = 'Inactive';
                $color = 'danger';
                if ($subscription->ends_at < now()) {
                    $status = 'Expirée';
                    $color = 'secondary';
                } elseif ($subscription->is_active) {
                    $status = 'Active';
                    $color = 'success';
                }
            @endphp

            <p>
                <strong>Statut :</strong> 
                <span class="badge badge-{{ $color }}">{{ $status }}</span>
            </p>
        </div>
    </div>
</div>
@endsection
