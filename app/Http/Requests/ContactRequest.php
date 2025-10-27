<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        // Si tu as une logique d'autorisation, tu peux la mettre ici.
        // Pour l'instant, on autorise tous les tenants.
        return true;
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        $tenantId = auth()->user()->tenant_id;

        // Pour update, récupérer l'ID du contact à ignorer pour la règle unique
        $contactId = $this->route('contact')?->id;

        return [
            'fullname' => ['required', 'string', 'max:255'],
            'phone_number' => [
                'required',
                'string',
                'max:15',
                Rule::unique('contacts')->ignore($contactId)->where(function ($query) use ($tenantId) {
                    $query->where('tenant_id', $tenantId);
                }),
            ],
            'address' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['client', 'supplier'])],
        ];
    }

    /**
     * Messages personnalisés.
     */
    public function messages(): array
    {
        return [
            'fullname.required' => 'Le nom complet est obligatoire.',
            'phone_number.required' => 'Le numéro de téléphone est obligatoire.',
            'phone_number.unique' => 'Ce numéro de téléphone est déjà utilisé pour ce tenant.',
            'address.required' => 'L\'adresse est obligatoire.',
            'type.required' => 'Le type de contact est obligatoire.',
            'type.in' => 'Le type de contact doit être client ou fournisseur.',
        ];
    }
}
