<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // L'utilisateur doit être connecté et appartenir à un tenant
        return true;
    }

    /**
     * Prépare les données avant validation (génère un slug si absent).
     */
    protected function prepareForValidation(): void
    {
        if (! $this->has('slug') && $this->filled('name')) {
            $this->merge([
                'slug' => Str::slug($this->input('name')),
            ]);
        }
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id; // route param -> category
        $tenantId = auth()->user()->tenant_id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->where(fn ($query) => $query->where('tenant_id', $tenantId))
                    ->ignore($categoryId, 'id'),
            ],
            'slug' => [
                'string',
                'max:50',
                Rule::unique('categories', 'slug')
                    ->where(fn ($query) => $query->where('tenant_id', $tenantId))
                    ->ignore($categoryId, 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la catégorie est obligatoire.',
            'name.unique'   => 'Cette catégorie existe déjà pour votre entreprise.',
            'slug.required' => "Le slug est obligatoire.",
            'slug.unique'   => 'Ce slug existe déjà pour votre entreprise.',
        ];
    }
}
