<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceSupplierRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     */
    public function authorize(): bool
    {
        // true si tout utilisateur connecté peut créer une facture
        return true;
    }

    /**
     * Règles de validation pour la création d'une facture fournisseur.
     */
    public function rules(): array
    {
        return [
            'invoice_number' => 'nullable|string|max:50|unique:invoices,invoice_number',
            'invoice_date' => 'required|date|before_or_equal:today',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'note' => 'nullable|string|max:1000',
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',

            // Validation des lignes de facture
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Messages personnalisés pour chaque règle.
     */
    public function messages(): array
    {
        return [
            'invoice_number.unique' => 'Ce numéro de facture existe déjà.',
            'invoice_date.required' => 'La date de facture est obligatoire.',
            'invoice_date.date' => 'La date de facture n’est pas valide.',
            'invoice_date.before_or_equal' => 'La date de facture ne peut pas être dans le futur.',
            'due_date.date' => 'La date d’échéance n’est pas valide.',
            'due_date.after_or_equal' => 'La date d’échéance doit être égale ou postérieure à la date de facture.',
            'supplier_id.required' => 'Vous devez sélectionner un fournisseur.',
            'supplier_id.exists' => 'Le fournisseur sélectionné est invalide.',
            'warehouse_id.required' => 'Vous devez sélectionner un entrepôt.',
            'warehouse_id.exists' => 'L’entrepôt sélectionné est invalide.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné est invalide.',
            'items.required' => 'Vous devez ajouter au moins un produit.',
            'items.array' => 'Les lignes de produits sont invalides.',
            'items.*.product_id.required' => 'Chaque ligne doit avoir un produit.',
            'items.*.product_id.exists' => 'Le produit sélectionné est invalide.',
            'items.*.quantity.required' => 'Chaque ligne doit avoir une quantité.',
            'items.*.quantity.integer' => 'La quantité doit être un entier.',
            'items.*.quantity.min' => 'La quantité doit être au moins de 1.',
            'items.*.purchase_price.required' => 'Chaque ligne doit avoir un prix d’achat.',
            'items.*.purchase_price.numeric' => 'Le prix d’achat doit être un nombre.',
            'items.*.purchase_price.min' => 'Le prix d’achat doit être au moins de 0.',
            'items.*.discount.numeric' => 'La remise doit être un nombre.',
            'items.*.discount.min' => 'La remise doit être au moins de 0.',
        ];
    }
}
