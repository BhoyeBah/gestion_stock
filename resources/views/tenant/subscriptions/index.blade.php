@extends('back.layouts.admin')

@section('content')
<style>
/* Loader circulaire style */
.circular-progress {
    position: relative;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: conic-gradient(var(--progress-color) calc(var(--progress) * 1%), #e9ecef 0);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: var(--progress-color);
    font-size: 1.2rem;
}
</style>

<div class="mb-4">
    <h1 class="h3 text-gray-800 d-flex align-items-center">
        <i class="fas fa-receipt text-primary mr-2"></i> Mes souscriptions
    </h1>
</div>

@if($subscriptions->count() > 0)
    <div class="row">
        @foreach($subscriptions as $subscription)
            @php
                $today = now();
                $totalDays = $subscription->starts_at->diffInDays($subscription->ends_at);
                $daysLeft = max(0, $today->diffInDays($subscription->ends_at, false));
                $progress = $totalDays > 0 ? round((($totalDays - $daysLeft) / $totalDays) * 100) : 100;

                // Déterminer couleur selon statut
                if ($daysLeft <= 0) {
                    $status = 'Expirée';
                    $statusClass = 'secondary';
                    $progressColor = '#6c757d';
                } elseif ($daysLeft <= 5) {
                    $status = 'Bientôt expirée';
                    $statusClass = 'warning';
                    $progressColor = '#ffc107';
                } elseif ($subscription->is_active) {
                    $status = 'Active';
                    $statusClass = 'success';
                    $progressColor = '#28a745';
                } else {
                    $status = 'Inactive';
                    $statusClass = 'danger';
                    $progressColor = '#dc3545';
                }
            @endphp

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body text-center">
                        {{-- Cercle de progression --}}
                        <div class="mx-auto mb-3 circular-progress"
                             style="--progress:{{ $progress }}; --progress-color:{{ $progressColor }}">
                            {{ $progress }}%
                        </div>

                        <h5 class="card-title text-primary">{{ $subscription->plan->name }}</h5>
                        <span class="badge badge-{{ $statusClass }}">{{ $status }}</span>

                        <p class="mt-2 mb-1 text-muted">
                            Montant payé : <strong>{{ number_format($subscription->amount_paid, 0, ',', ' ') }} FCFA</strong>
                        </p>
                        <p class="mb-1">
                            Méthode : <strong>{{ $subscription->payment_method ?? '-' }}</strong>
                        </p>
                        <p class="small text-muted">
                            Du {{ $subscription->starts_at->format('d/m/Y') }} au {{ $subscription->ends_at->format('d/m/Y') }}
                        </p>
                        <p class="mt-2">
                            @if($daysLeft > 0)
                                <strong>{{ $daysLeft }} jours restants</strong>
                            @else
                                <strong>Abonnement expiré</strong>
                            @endif
                        </p>

                        <form action="{{ route('tenant.subscriptions.show', $subscription) }}" method="GET" target="_blank">
                            <button type="submit" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="fas fa-print"></i> Voir / Imprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $subscriptions->links() }}
    </div>
@else
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle"></i> Vous n’avez aucune souscription pour le moment.
    </div>
@endif
@endsection
