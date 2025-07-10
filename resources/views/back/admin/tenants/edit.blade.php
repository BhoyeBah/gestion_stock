@extends('back.layouts.admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Modifier une entreprise</h1>
    <a href="{{ route('admin.tenants.index') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('admin.tenants.update', $tenant->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Infos Entreprise -->
            <h5 class="text-primary mb-3">Informations de l’entreprise</h5>
            <div class="form-group">
                <label for="name">Nom de l’entreprise *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="slug">Slug (identifiant unique) *</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $tenant->slug) }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $tenant->email) }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $tenant->phone) }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="logo">Logo</label><br>
                @if ($tenant->logo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo" height="50">
                    </div>
                @endif
                <input type="file" name="logo" id="logo" class="form-control-file">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ old('is_active', $tenant->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="form-check-label">Entreprise active</label>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection
