<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true; // true si tous les utilisateurs authentifiés peuvent créer/modifier
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        $unitId = $this->route('unit') ? $this->route('unit')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units', 'name')->ignore($unitId),
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('units', 'code')->ignore($unitId),
            ],
        ];
    }

    /**
     * Messages personnalisés (optionnel)
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de l’unité est obligatoire.',
            'name.unique' => 'Cette unité existe déjà.',
            'code.required' => 'Le code est obligatoire.',
            'code.unique' => 'Ce code existe déjà.',
        ];
    }
}
