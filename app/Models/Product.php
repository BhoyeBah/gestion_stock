<?php

namespace App\Models;

use App\Traits\HasTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasTenant, HasUuid;

    protected $fillable = [
        'category_id',
        'unit_id',
        'name',
        'description',
        'price',
        'seuil_alert',
        'is_active',
        'image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'integer',
        'seuil_alert' => 'integer',
    ];

    /**
     * Relations
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Units::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class,      // Modèle final
            InvoiceItem::class,  // Modèle intermédiaire
            'product_id',        // clé étrangère dans invoice_items vers products
            'invoice_id',        // clé étrangère dans payments vers invoices
            'id',                // clé primaire de products
            'invoice_id'         // clé locale dans invoice_items vers invoices
        );
    }

    public function invoices()
    {
        return $this->hasManyThrough(
            Invoice::class,      // modèle final
            InvoiceItem::class,  // modèle intermédiaire
            'product_id',        // invoice_items.product_id -> products.id
            'id',                // invoices.id (colonne utilisée dans la jointure)
            'id',                // products.id (local key)
            'invoice_id'         // invoice_items.invoice_id (clé locale du through)
        );
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    /**
     * Accessor pour obtenir l'URL complète de l'image
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/products/'.$this->image) : null;
    }
}
