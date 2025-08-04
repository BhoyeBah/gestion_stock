@extends('back.layouts.admin')

@section('content')
<!-- En-tête de page -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">➕ Ajouter un utilisateur</h1>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<!-- Formulaire de création -->
<div class="card shadow border-left-primary">
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <!-- Nom -->
            <div class="form-group">
                <label for="name" class="font-weight-bold">Nom complet <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="Ex: Jean Dupont" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group mt-3">
                <label for="email" class="font-weight-bold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="Ex: exemple@mail.com" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Téléphone -->
            <div class="form-group mt-3">
                <label for="phone" class="font-weight-bold">Téléphone</label>
                <input type="text" name="phone" id="phone"
                       class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone') }}" placeholder="Ex: +221 77 123 45 67">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Mot de passe -->
            <div class="form-group mt-3">
                <label for="password" class="font-weight-bold">Mot de passe <span class="text-danger">*</span></label>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Mot de passe" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirmation mot de passe -->
            <div class="form-group mt-3">
                <label for="password_confirmation" class="font-weight-bold">Confirmer le mot de passe <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control" placeholder="Confirmer le mot de passe" required>
            </div>

            <!-- Rôle -->
            <div class="form-group mt-4">
                <label for="role" class="font-weight-bold">Rôle <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                    <option value="" disabled selected>-- Sélectionnez un rôle --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Statut actif -->
            <div class="form-group mt-4">
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="custom-control-input" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="custom-control-label font-weight-bold" for="is_active">Activer le compte</label>
                </div>
            </div>

            <!-- Bouton de soumission -->
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Créer l'utilisateur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
