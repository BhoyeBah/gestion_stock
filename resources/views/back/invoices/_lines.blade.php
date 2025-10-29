@php
    $invoiceItems = isset($invoice) && $invoice->items ? $invoice->items : collect([]);
@endphp

<table class="table table-bordered" id="invoiceLinesTable">
    <thead class="thead-light">
        <tr>
            <th>Entrêpot</th>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix d'achat</th>
            <th>Remise</th>
            <th>Total</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($invoiceItems as $index => $item)
            <tr>
                {{-- Entrêpot --}}
                <td>
                    <select name="items[{{ $index }}][warehouse_id]" class="form-control warehouseSelect" required>
                        <option value="">Sélectionnez un entrepôt</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}"
                                {{ $item->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </td>

                {{-- Produit --}}
                <td>
                    <select name="items[{{ $index }}][product_id]" class="form-control productSelect" required>
                        <option value="">Sélectionnez un produit</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </td>

                {{-- Quantité / Prix / Remise --}}
                <td><input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity"
                        value="{{ $item->quantity }}" min="1" required></td>
                <td><input type="number" name="items[{{ $index }}][unit_price]" class="form-control unit_price"
                        value="{{ $item->unit_price ?? 0 }}" min="0" required></td>
                <td><input type="number" name="items[{{ $index }}][discount]" class="form-control discount"
                        value="{{ $item->discount ?? 0 }}" min="0"></td>
                <td class="total_line">{{ $item->quantity * ($item->unit_price ?? 0) - ($item->discount ?? 0) }}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger removeLineBtn"><i
                            class="fas fa-trash"></i></button>
                </td>
            </tr>
        @empty
            <tr>
                <td>
                    <select name="items[0][warehouse_id]" class="form-control warehouseSelect" required>
                        <option value="">Sélectionnez un entrepôt</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="items[0][product_id]" class="form-control productSelect" required>
                        <option value="">Sélectionnez un produit</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="items[0][quantity]" class="form-control quantity" value="1"
                        min="1" required></td>
                <td><input type="number" name="items[0][unit_price]" class="form-control unit_price" value="0"
                        min="0" required></td>
                <td><input type="number" name="items[0][discount]" class="form-control discount" value="0"
                        min="0"></td>
                <td class="total_line">0</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger removeLineBtn"><i
                            class="fas fa-trash"></i></button>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<button type="button" class="btn btn-sm btn-primary mt-2" id="addLineBtn">
    <i class="fas fa-plus"></i> Ajouter une ligne
</button>

<div class="mt-3 text-right">
    <strong>Total Réduction : </strong> <span id="invoiceDiscountTotal">0</span> FCFA<br>
    <strong>Total Facture : </strong> <span id="invoiceTotal">0</span> FCFA
</div>

@push('scripts')
    <script>
        let lineIndex = document.querySelectorAll('#invoiceLinesTable tbody tr').length;

        function toNumber(v) {
            const n = Number(String(v).replace(/\s+/g, ''));
            return isFinite(n) ? n : 0;
        }

        function updateLineTotal(row) {
            const qty = toNumber(row.querySelector('.quantity').value);
            const price = toNumber(row.querySelector('.unit_price').value);
            const discount = toNumber(row.querySelector('.discount').value);
            const total = Math.max(0, qty * price - discount);
            row.querySelector('.total_line').textContent = total;
            updateInvoiceTotals();
        }

        function updateInvoiceTotals() {
            let total = 0,
                discountTotal = 0;
            document.querySelectorAll('#invoiceLinesTable tbody tr').forEach(row => {
                total += toNumber(row.querySelector('.total_line').textContent);
                discountTotal += toNumber(row.querySelector('.discount').value);
            });
            document.getElementById('invoiceTotal').textContent = total;
            document.getElementById('invoiceDiscountTotal').textContent = discountTotal;
        }

        // ✅ Empêcher le même produit dans le même entrepôt uniquement
        function checkDuplicateWarehouseProduct() {
            const rows = document.querySelectorAll('#invoiceLinesTable tbody tr');
            const combinations = new Set();
            let hasDuplicate = false;

            rows.forEach(row => {
                const warehouse = row.querySelector('.warehouseSelect')?.value;
                const product = row.querySelector('.productSelect')?.value;
                const combo = `${warehouse}-${product}`;

                if (warehouse && product) {
                    if (combinations.has(combo)) {
                        row.classList.add('table-danger');
                        hasDuplicate = true;
                    } else {
                        combinations.add(combo);
                        row.classList.remove('table-danger');
                    }
                } else {
                    row.classList.remove('table-danger');
                }
            });

            if (hasDuplicate) {
                alert('Le même produit ne peut pas être sélectionné deux fois dans le même entrepôt.');
                return false;
            }
            return true;
        }

        // ✅ Corrigée : ne bloque pas le même produit dans un entrepôt différent
        function updateProductOptions() {
            const rows = document.querySelectorAll('#invoiceLinesTable tbody tr');

            rows.forEach(row => {
                const currentWarehouse = row.querySelector('.warehouseSelect')?.value;
                const currentProductSelect = row.querySelector('.productSelect');

                Array.from(currentProductSelect.options).forEach(option => {
                    if (option.value === "") {
                        option.disabled = false;
                        return;
                    }

                    let disable = false;
                    rows.forEach(otherRow => {
                        if (otherRow === row) return; // ignorer soi-même
                        const otherWarehouse = otherRow.querySelector('.warehouseSelect')?.value;
                        const otherProduct = otherRow.querySelector('.productSelect')?.value;
                        if (currentWarehouse && otherWarehouse && currentWarehouse ===
                            otherWarehouse && otherProduct === option.value) {
                            disable = true;
                        }
                    });

                    option.disabled = disable;
                });
            });
        }

        function reindexRows() {
            const rows = document.querySelectorAll('#invoiceLinesTable tbody tr');
            rows.forEach((row, i) => {
                const wh = row.querySelector('.warehouseSelect');
                const sel = row.querySelector('.productSelect');
                const qty = row.querySelector('.quantity');
                const price = row.querySelector('.unit_price');
                const disc = row.querySelector('.discount');
                if (wh) wh.setAttribute('name', `items[${i}][warehouse_id]`);
                if (sel) sel.setAttribute('name', `items[${i}][product_id]`);
                if (qty) qty.setAttribute('name', `items[${i}][quantity]`);
                if (price) price.setAttribute('name', `items[${i}][unit_price]`);
                if (disc) disc.setAttribute('name', `items[${i}][discount]`);
            });
            lineIndex = rows.length;
        }

        document.querySelector('#invoiceLinesTable tbody').addEventListener('change', function(e) {
            if (e.target.classList.contains('warehouseSelect') || e.target.classList.contains('productSelect')) {
                checkDuplicateWarehouseProduct();
                updateProductOptions(); // ✅ à chaque changement
            }

            if (e.target.classList.contains('productSelect')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const price = selectedOption ? selectedOption.dataset.price || 0 : 0;
                const row = e.target.closest('tr');
                row.querySelector('.unit_price').value = price;
                updateLineTotal(row);
            }
        });

        document.getElementById('addLineBtn').addEventListener('click', () => {
            const tbody = document.querySelector('#invoiceLinesTable tbody');
            const rowHtml = `
<tr>
    <td>
        <select name="items[${lineIndex}][warehouse_id]" class="form-control warehouseSelect" required>
            <option value="">Sélectionnez un entrepôt</option>
            @foreach ($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <select name="items[${lineIndex}][product_id]" class="form-control productSelect" required>
            <option value="">Sélectionnez un produit</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
            @endforeach
        </select>
    </td>
    <td><input type="number" name="items[${lineIndex}][quantity]" class="form-control quantity" value="1" min="1" required></td>
    <td><input type="number" name="items[${lineIndex}][unit_price]" class="form-control unit_price" value="0" min="0" required></td>
    <td><input type="number" name="items[${lineIndex}][discount]" class="form-control discount" value="0" min="0"></td>
    <td class="total_line">0</td>
    <td class="text-center"><button type="button" class="btn btn-sm btn-danger removeLineBtn"><i class="fas fa-trash"></i></button></td>
</tr>`;
            tbody.insertAdjacentHTML('beforeend', rowHtml);
            const newRow = tbody.querySelector('tr:last-child');
            updateLineTotal(newRow);
            reindexRows();
            updateProductOptions();
            updateInvoiceTotals();
            checkDuplicateWarehouseProduct();
        });

        document.querySelector('#invoiceLinesTable tbody').addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity') || e.target.classList.contains('unit_price') || e.target
                .classList.contains('discount')) {
                updateLineTotal(e.target.closest('tr'));
            }
        });

        document.querySelector('#invoiceLinesTable tbody').addEventListener('click', function(e) {
            const btn = e.target.closest('.removeLineBtn');
            if (btn) {
                const tr = btn.closest('tr');
                if (tr) tr.remove();
                reindexRows();
                updateProductOptions();
                updateInvoiceTotals();
                checkDuplicateWarehouseProduct();
            }
        });

        document.querySelectorAll('#invoiceLinesTable tbody tr').forEach(row => updateLineTotal(row));
        reindexRows();
        updateProductOptions();
    </script>
@endpush
