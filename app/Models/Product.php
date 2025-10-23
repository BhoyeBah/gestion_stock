<?php

namespace App\Models;

use App\Traits\HasTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    /**
     * Accessor pour obtenir l'URL complète de l'image
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/products/' . $this->image) : null;
    }
}
