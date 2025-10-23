<form action="{{ $route }}" method="{{ $method === 'PUT' ? 'POST' : $method }}">
    @csrf
    @if (in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    @if ($method == 'POST')
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
                <i class="fas {{ $method === 'POST' ? 'fa-plus-circle' : 'fa-edit' }}"></i>
                {{ $method === 'POST' ? 'Ajouter un client' : 'Modifier le client' }}
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="modal-body">
        <div class="form-group">
            <label for="full_name">Nom complet <span class="text-danger">*</span></label>
            <input type="text" name="full_name" class="form-control" id="full_name" placeholder="Ex: John Doe"
                value="{{ old('full_name', $client->full_name ?? '') }}" required>
            @error('full_name')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Adresse <span class="text-danger">*</span></label>
            <input type="text" name="address" class="form-control" id="address"
                placeholder="Ex: 123 Rue Principale" value="{{ old('address', $client->address ?? '') }}" required>
            @error('address')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email"
                placeholder="Ex: contact@client.com" value="{{ old('email', $client->email ?? '') }}">
            @error('email')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone_number">Numéro de téléphone <span class="text-danger">*</span></label>
            <input type="text" name="phone_number" class="form-control" id="phone_number"
                placeholder="Ex: 77 123 45 67" value="{{ old('phone_number', $client->phone_number ?? '') }}" required>
            @error('phone_number')
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
