@extends('back.layouts.admin')

@section('content')
<!-- En-tête -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Modifier la permission</h1>
    <a href="{{ route('admin.permissions.index') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>
</div>

<!-- Formulaire -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.permissions.update', $permission->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nom technique (unique) *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="name" name="name" value="{{ old('name', $permission->name) }}" required>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <small class="form-text text-muted">Ex : <code>manage_users</code>, <code>view_reports</code></small>
            </div>

            <div class="form-group">
                <label for="description">Description (facultatif)</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          id="description" name="description" rows="3">{{ old('description', $permission->description) }}</textarea>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection
