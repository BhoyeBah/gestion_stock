@extends('back.layouts.admin')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 text-gray-800">✏️ Modifier le rôle</h1>
    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<!-- Edit Form -->
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Tenant -->
            <div class="form-group">
                <label for="tenant_id" class="font-weight-bold">Entreprise concernée <span class="text-danger">*</span></label>
                <select name="tenant_id" id="tenant_id" class="form-control @error('tenant_id') is-invalid @enderror" required {{ !auth()->user()->is_platform_user() ? 'disabled' : '' }}>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ (old('tenant_id', $role->tenant_id) == $tenant->id) ? 'selected' : '' }}>
                            {{ $tenant->name }}
                        </option>
                    @endforeach
                </select>
                @error('tenant_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if(!auth()->user()->is_platform_user())
                    <!-- Champ caché pour soumettre quand le select est désactivé -->
                    <input type="hidden" name="tenant_id" value="{{ $role->tenant_id }}">
                @endif
            </div>

            <!-- Nom -->
            <div class="form-group mt-3">
                <label for="name" class="font-weight-bold">Nom du rôle <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Ex: manager, vendeur, caissier..."
                       value="{{ old('name', Str::after($role->name, '_')) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Permissions -->
            <div class="form-group mt-4">
                <label class="font-weight-bold">Permissions associées</label>
                <div class="row">
                    @foreach($permissions as $permission)
                       
                        <div class="col-md-4 col-sm-6 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="perm_{{ $permission->id }}"
                                       name="permissions[]"
                                       value="{{ $permission->id }}"
                                       {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="perm_{{ $permission->id }}">
                                    {{ ucfirst(str_replace('_', ' ', $permission->description)) }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('permissions')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit -->
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
