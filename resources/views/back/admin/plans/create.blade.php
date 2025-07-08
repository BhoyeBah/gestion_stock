@extends('back.layouts.admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📝 Ajouter un nouveau plan</h1>
    <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>
</div>

<div class="card shadow border-left-primary">
    <div class="card-header bg-primary text-white">
        <h6 class="m-0 font-weight-bold">Formulaire d'ajout d’un plan d’abonnement</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.plans.store') }}">
            @csrf

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Nom du plan *</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="slug">Identifiant (slug) *</label>
                    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="price">Prix (FCFA) *</label>
                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="duration_days">Durée (jours) *</label>
                    <input type="number" class="form-control" id="duration_days" name="duration_days" value="{{ old('duration_days') }}" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="max_users">Max utilisateurs</label>
                    <input type="number" class="form-control" id="max_users" name="max_users" value="{{ old('max_users') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="max_storage_mb">Stockage max (en Mo)</label>
                <input type="number" class="form-control" id="max_storage_mb" name="max_storage_mb" value="{{ old('max_storage_mb') }}">
            </div>

            <div class="form-group">
                <label for="description">Description (facultative)</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Décrivez brièvement le plan">{{ old('description') }}</textarea>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ old('is_active') ? 'checked' : '' }}>
                <label class="form-check-label font-weight-bold text-success" for="is_active">
                    <i class="fas fa-check-circle"></i> Activer ce plan
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer le plan
            </button>
        </form>
    </div>
</div>
@endsection
