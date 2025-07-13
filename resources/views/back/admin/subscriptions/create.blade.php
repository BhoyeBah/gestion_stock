@extends('back.layouts.admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">➕ Nouvelle souscription</h1>
    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card shadow border-left-primary">
    <div class="card-body">
        <form action="{{ route('admin.subscriptions.store') }}" method="POST">
            @csrf

            {{-- Tenant --}}
            <div class="form-group">
                <label for="tenant_id">Entreprise <span class="text-danger">*</span></label>
                <select name="tenant_id" id="tenant_id" class="form-control" required>
                    <option value="">-- Sélectionnez --</option>
                    @foreach($tenants as $tenant)
                        @if($tenant->slug !== "platform")
                            <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                {{ $tenant->name }} ({{ $tenant->slug }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            {{-- Plan --}}
            <div class="form-group">
                <label for="plan_id">Plan d’abonnement <span class="text-danger">*</span></label>
                <select name="plan_id" id="plan_id" class="form-control" required>
                    <option value="">-- Sélectionnez --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} ({{ number_format($plan->price, 0, ',', ' ') }} FCFA)
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Amount Paid --}}
            <div class="form-group">
                <label for="amount_paid">Montant payé (FCFA) <span class="text-danger">*</span></label>
                <input type="number" name="amount_paid" class="form-control" required min="0" value="{{ old('amount_paid', 0) }}">
            </div>

            {{-- Payment Method --}}
            <div class="form-group">
                <label for="payment_method">Moyen de paiement</label>
                <input type="text" name="payment_method" class="form-control" placeholder="Wave, Orange Money..." value="{{ old('payment_method') }}">
            </div>

            {{-- Dates --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="starts_at">Date de début <span class="text-danger">*</span></label>
                        <input type="date" name="starts_at" class="form-control" required value="{{ old('starts_at') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ends_at">Date de fin <span class="text-danger">*</span></label>
                        <input type="date" name="ends_at" class="form-control" required value="{{ old('ends_at') }}">
                    </div>
                </div>
            </div>

            

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</div>
@endsection
