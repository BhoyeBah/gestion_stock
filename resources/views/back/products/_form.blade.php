<form action="{{ $route }}" method="{{ $method === 'PUT' ? 'POST' : $method }}" enctype="multipart/form-data">
    @csrf
    @if (in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    @if ($method == "POST")
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
                <i class="fas {{ $method === 'POST' ? 'fa-plus-circle' : 'fa-edit' }}"></i>
                {{ $method === 'POST' ? 'Ajouter un produit' : 'Modifier le produit' }}
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="modal-body">
        <div class="row">
            <!-- Colonne gauche -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Nom du produit <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" id="name"
                        placeholder="Ex: Boisson gazeuse" value="{{ old('name', $product->name ?? '') }}" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id">Catégorie <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">-- Sélectionner une catégorie --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ isset($product) && $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Prix</label>
                    <input type="number" name="price" class="form-control" placeholder="Ex: 1500"
                        value="{{ old('price', $product->price ?? 0) }}">
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="unit_id">Unité <span class="text-danger">*</span></label>
                    <select name="unit_id" id="unit_id" class="form-control" required>
                        <option value="">-- Sélectionner une unité --</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}"
                                {{ isset($product) && $product->unit_id == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="seuil_alert">Seuil d'alerte</label>
                    <input type="number" name="seuil_alert" class="form-control" placeholder="Ex: 10"
                        value="{{ old('seuil_alert', $product->seuil_alert ?? 10) }}">
                </div>

                <div class="form-group">
                    <label for="image">Image du produit</label>
                    <input type="file" name="image" class="form-control" placeholder="Choisir une image">
                </div>
            </div>

            <!-- Champ description sur toute la largeur -->
            <div class="col-12">
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3"
                        placeholder="Décrivez le produit (ex: saveur, format, particularités...)">{{ old('description', $product->description ?? '') }}</textarea>
                </div>
            </div>
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
