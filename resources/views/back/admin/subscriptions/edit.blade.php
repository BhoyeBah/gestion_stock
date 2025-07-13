@extends('back.layouts.admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Modifier la souscription</h1>
    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.subscriptions.update', $subscription->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="tenant_id">Entreprise *</label>
                <select name="tenant_id" id="tenant_id" class="form-control" required>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ $subscription->tenant_id == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="plan_id">Plan d’abonnement *</label>
                <select name="plan_id" id="plan_id" class="form-control" required>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ $subscription->plan_id == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} ({{ number_format($plan->price, 0, ',', ' ') }} FCFA / {{ $plan->duration_days }} jours)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="amount_paid">Montant payé (FCFA)</label>
                <input type="number" name="amount_paid" id="amount_paid" class="form-control"
                       value="{{ old('amount_paid', $subscription->amount_paid) }}" required>
            </div>

            <div class="form-group">
                <label for="payment_method">Mode de paiement</label>
                <input type="text" name="payment_method" id="payment_method" class="form-control"
                       value="{{ old('payment_method', $subscription->payment_method) }}">
            </div>

            <div class="form-group">
                <label for="starts_at">Date de début *</label>
                <input type="datetime-local" name="starts_at" id="starts_at" class="form-control"
                       value="{{ old('starts_at', $subscription->starts_at->format('Y-m-d\TH:i')) }}" required>
            </div>

            <div class="form-group">
                <label for="ends_at">Date de fin *</label>
                <input type="datetime-local" name="ends_at" id="ends_at" class="form-control"
                       value="{{ old('ends_at', $subscription->ends_at->format('Y-m-d\TH:i')) }}" required>
            </div>

            

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection
