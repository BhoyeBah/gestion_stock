@php
    $users = $users ?? auth()->user()->tenant->users()->get();
@endphp

<form action="{{ $route }}" method="{{ $method === 'PUT' ? 'POST' : $method }}">
    @csrf
    @if (in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    @if ($method == 'POST')
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
                <i class="fas {{ $method === 'POST' ? 'fa-plus-circle' : 'fa-edit' }}"></i>
                {{ $method === 'POST' ? 'Ajouter un entrepôt' : 'Modifier l’entrepôt' }}
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="modal-body">
        {{-- Nom de l’entrepôt --}}
        <div class="form-group">
            <label for="name">Nom de l’entrepôt <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="name"
                placeholder="Ex: Entrepôt Central"
                value="{{ old('name', $warehouse->name ?? '') }}" required>
            @error('name')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>

        {{-- Adresse --}}
        <div class="form-group">
            <label for="address">Adresse</label>
            <input type="text" name="address" class="form-control" id="address"
                placeholder="Ex: Zone industrielle de Dakar"
                value="{{ old('address', $warehouse->address ?? '') }}">
            @error('address')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>

        {{-- Responsable --}}
        <div class="form-group">
            <label for="manager_id">Responsable</label>
            <select name="manager_id" id="manager_id" class="form-control">
                <option value="">-- Sélectionner un responsable --</option>
                @foreach ($users as $user)

                    <option value="{{ $user->id }}"
                        {{ old('manager_id', $warehouse->manager_id ?? '') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('manager_id')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>

        {{-- Description --}}
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"
                placeholder="Ex: Entrepôt principal servant de stockage des produits finis.">{{ old('description', $warehouse->description ?? '') }}</textarea>
            @error('description')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>



    </div>

    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times"></i> Annuler
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas {{ $method === 'POST' ? 'fa-save' : 'fa-check' }}"></i>
            {{ $method === 'POST' ? 'Ajouter' : 'Enregistrer les modifications' }}
        </button>
    </div>
</form>
