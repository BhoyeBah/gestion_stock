<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        // Autoriser tous les tenants pour l'instant
        return true;
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        $tenantId = auth()->user()->tenant_id;

        // Pour update, récupérer l'ID de la facture à ignorer pour la règle unique
        $invoiceId = $this->route('invoice')?->id;

        return [
            'contact_id' => ['required', 'uuid', 'exists:contacts,id'],
            'invoice_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('invoices')->ignore($invoiceId)->where(fn ($query) => $query->where('tenant_id', $tenantId)),
            ],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:invoice_date'],
            'type' => ['required', Rule::in(['client', 'supplier'])],

            // Validation des lignes de facture
            'items' => ['required', 'array', 'min:1'],
            'items.*.warehouse_id' => ['required', 'uuid', 'exists:warehouses,id'],
            'items.*.product_id' => ['required', 'uuid', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'integer', 'min:0'],
            'items.*.discount' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [
            'invoice_number.unique' => 'Ce numéro de facture existe déjà pour ce tenant.',
            'contact_id.required' => 'Le contact est requis.',
            'invoice_date.required' => 'La date de facture est obligatoire.',
            'items.required' => 'La facture doit contenir au moins une ligne.',
            'items.*.warehouse_id.required' => 'Chaque ligne doit avoir un entrepôt.',
            'items.*.product_id.required' => 'Chaque ligne doit avoir un produit.',
            'items.*.quantity.required' => 'Chaque ligne doit avoir une quantité.',
            'items.*.unit_price.required' => 'Chaque ligne doit avoir un prix unitaire.',
        ];
    }
}
