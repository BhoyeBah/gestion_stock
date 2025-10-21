<form action="{{ $route }}" method="{{ $method === 'PUT' ? 'POST' : $method }}">
    @csrf
    @if (in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

@if ($method == 'POST')

<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="fas {{ $method === 'POST' ? 'fa-plus-circle' : 'fa-edit' }}"></i>
        {{ $method === 'POST' ? 'Ajouter une unité' : 'Modifier l’unité' }}
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
<div class="modal-body">
    <div class="form-group">
        <label for="name">Nom de l’unité <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" id="name"
               placeholder="Ex: Kilogramme" value="{{ old('name', $unit->name ?? '') }}" required>
        @error('name')
            <small class="text-danger d-block mt-1">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group">
        <label for="code">Code <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control" id="code"
               placeholder="Ex: kg" value="{{ old('code', $unit->code ?? '') }}" required>
        @error('code')
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
