<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
                Rule::unique('suppliers')
                    ->where(fn($query) => $query->where('tenant_id', $tenantId))
                    ->ignore($this->supplier?->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Le nom du fournisseur est obligatoire.',
            'address.required' => 'L’adresse du fournisseur est obligatoire.',
            'phone_number.required' => 'Le numéro de téléphone est obligatoire.',
            'phone_number.unique' => 'Ce numéro existe déjà pour votre entreprise.',
        ];
    }
}
