<?php

namespace App\Models;

use App\Traits\HasTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasTenant,HasUuid;

    protected $fillable = [
        'name',
        'slug',
        'tenant_id',
    ];

    // Relation avec les produits
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
