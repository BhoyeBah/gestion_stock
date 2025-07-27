@extends('back.layouts.admin')

@section('content')
<!-- En-tête de page -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">✏️ Modifier l'utilisateur</h1>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<!-- Formulaire de modification -->
<div class="card shadow border-0">
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nom -->
            <div class="form-group">
                <label for="name" class="font-weight-bold">Nom complet <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group mt-3">
                <label for="email" class="font-weight-bold">Adresse email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Téléphone -->
            <div class="form-group mt-3">
                <label for="phone" class="font-weight-bold">Téléphone</label>
                <input type="text" name="phone" id="phone"
                       class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Mot de passe (optionnel) -->
            <div class="form-group mt-3">
                <label for="password" class="font-weight-bold">Mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirmation mot de passe -->
            <div class="form-group mt-3">
                <label for="password_confirmation" class="font-weight-bold">Confirmation du mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control">
            </div>

            <!-- Rôle -->
            <div class="form-group mt-3">
                <label for="role" class="font-weight-bold">Rôle <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                    @foreach($roles as $role)
                        @php
                            $roleLabel = strtolower(\Illuminate\Support\Str::after($role->name, '_'));
                        @endphp
                        <option value="{{ $role->name }}"
                            {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                            {{ $roleLabel === 'admin' ? ucfirst($roleLabel) : strtolower($roleLabel) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Statut (pas modifiable si propriétaire) -->
            @if(!$user->is_owner)
                <div class="form-group mt-3">
                    <label for="is_active" class="font-weight-bold">Statut</label>
                    <select name="is_active" id="is_active" class="form-control @error('is_active') is-invalid @enderror">
                        <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @else
                <p class="mt-3 text-muted"><i class="fas fa-info-circle"></i> Le statut du propriétaire ne peut pas être modifié.</p>
            @endif

            <!-- Bouton -->
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
