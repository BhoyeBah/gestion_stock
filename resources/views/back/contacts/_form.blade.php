<form action="{{ $route }}" method="POST">
    @csrf
    @if (in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    <input type="hidden" name="type" value="{{ $type === 'clients' ? 'client' : 'supplier' }}">

    @if ($method == 'POST')
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
                <i class="fas {{ $method === 'POST' ? 'fa-plus-circle' : 'fa-edit' }}"></i>
                {{ $method === 'POST'
                    ? 'Ajouter un ' . ($type === 'clients' ? 'client' : 'fournisseur')
                    : 'Modifier le ' . ($type === 'clients' ? 'client' : 'fournisseur') }}
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif


    <div class="modal-body">
        <div class="form-group">
            <label for="fullname">Nom complet <span class="text-danger">*</span></label>
            <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Ex: John Doe"
                value="{{ old('fullname', $contact->fullname ?? '') }}" required>
            @error('fullname')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone_number">Numéro de téléphone <span class="text-danger">*</span></label>
            <input type="text" name="phone_number" id="phone_number" class="form-control"
                placeholder="Ex: 77 123 45 67" value="{{ old('phone_number', $contact->phone_number ?? '') }}" required>
            @error('phone_number')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Adresse <span class="text-danger">*</span></label>
            <input type="text" name="address" id="address" class="form-control"
                placeholder="Ex: 123 Rue Principale" value="{{ old('address', $contact->address ?? '') }}" required>
            @error('address')
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
