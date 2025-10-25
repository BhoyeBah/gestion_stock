<form action="{{ $route }}" method="{{ $method === 'PUT' ? 'POST' : $method }}">
    @csrf
    @if(in_array($method, ['PUT','PATCH','DELETE']))
        @method($method)
    @endif

    <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
            <i class="fas {{ $method === 'POST' ? 'fa-plus-circle' : 'fa-edit' }}"></i>
            {{ $method === 'POST' ? 'Nouvelle facture fournisseur' : 'Modifier la facture' }}
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="supplier_id">Fournisseur <span class="text-danger">*</span></label>
                <select name="supplier_id" id="supplier_id" class="form-control" required>
                    <option value="">Sélectionnez un fournisseur</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ (old('supplier_id', $invoice->supplier_id) == $supplier->id) ? 'selected' : '' }}>
                            {{ $supplier->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="warehouse_id">Entrepôt <span class="text-danger">*</span></label>
                <select name="warehouse_id" id="warehouse_id" class="form-control" required>
                    <option value="">Sélectionnez un entrepôt</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ (old('warehouse_id', $invoice->warehouse_id) == $warehouse->id) ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="invoice_date">Date facture <span class="text-danger">*</span></label>
                <input type="date" name="invoice_date" class="form-control"
                    value="{{ old('invoice_date', $invoice->invoice_date?->format('Y-m-d')) }}" required>
            </div>

            <div class="col-md-2">
                <label for="due_date">Date d’échéance</label>
                <input type="date" name="due_date" class="form-control"
                    value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}">
            </div>
        </div>

        <!-- Lignes facture -->
        @include('back.invoices._lines', ['invoice' => $invoice, 'products' => $products])
    </div>

    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times"></i> Annuler
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas {{ $method === 'POST' ? 'fa-save' : 'fa-check' }}"></i>
            {{ $method === 'POST' ? 'Créer' : 'Enregistrer les modifications' }}
        </button>
    </div>
</form>
