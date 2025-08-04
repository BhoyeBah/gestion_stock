<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Boot the HasUuid trait for a model.
     *
     * Cela génère un UUID automatiquement dans le champ `id` si vide.
     */
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Indique que la clé primaire n'est pas auto-incrémentée.
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Indique que la clé primaire est de type string.
     */
    public function getKeyType(): string
    {
        return 'string';
    }
}
