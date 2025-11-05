<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnRequestProduct extends FormRequest
{
    /**
     * Authorise la requête (sinon elle sera toujours refusée)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            'invoice_item_id'     => 'required|uuid|exists:invoice_items,id',
            'quantity'            => 'required|integer|min:1',
            'motif'               => 'required|string|max:255',
        ];
    }

    /**
     * Messages personnalisés (facultatif mais recommandé)
     */
    public function messages(): array
    {
        return [
            'invoice_item_id.required' => 'Le produit de facture est obligatoire.',
            'invoice_item_id.exists'   => 'Ce produit n’existe pas dans la facture.',
            'quantity.required'        => 'La quantité à retourner est requise.',
            'quantity.min'             => 'La quantité doit être au minimum 1.',
            'motif.required'           => 'Le motif du retour est obligatoire.',
        ];
    }
}
