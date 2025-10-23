<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette action.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour le formulaire Client.
     */
    public function rules(): array
    {

         $tenantId = auth()->user()->tenant_id ?? $this->tenant_id;

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('clients')
                    ->where(fn($query) => $query->where('tenant_id', $tenantId))
                    ->ignore($this->client?->id), // utile pour l’update
            ],
        ];
    }

    /**
     * Messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Le nom du client est obligatoire.',
            'address.required' => 'L’adresse du client est obligatoire.',
            'phone_number.required' => 'Le numéro de téléphone est obligatoire.',
            'phone_number.unique' => 'Ce numéro existe déjà pour votre entreprise.',
        ];
    }
}
