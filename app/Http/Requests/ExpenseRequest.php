<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     */
    public function authorize(): bool
    {
        // Autoriser toutes les requêtes pour les utilisateurs connectés
        return true;
    }

    /**
     * Règles de validation pour la requête.
     */
    public function rules(): array
    {
        return [
            'reason'       => ['required', 'string', 'max:255'],
            'amount'       => ['required', 'numeric', 'min:0'],
            'expense_date' => ['required', 'date'], 
        ];
    }

    /**
     * Messages d’erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'reason.required' => 'Le motif de la dépense est obligatoire.',
            'reason.string'   => 'Le motif doit être une chaîne de caractères.',
            'reason.max'      => 'Le motif ne doit pas dépasser 255 caractères.',

            'amount.required' => 'Le montant de la dépense est obligatoire.',
            'amount.numeric'  => 'Le montant doit être un nombre.',
            'amount.min'      => 'Le montant doit être supérieur ou égal à zéro.',

            'expense_date.required' => 'La date de la dépense est obligatoire.',
            'expense_date.date'     => 'La date de la dépense doit être une date valide.',
        ];
    }
}
