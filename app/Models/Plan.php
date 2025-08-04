<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Plan extends Model
{
    use HasFactory, HasUuid;
    

    protected $fillable = [
        'name',
        'slug',
        'price',
        'duration_days',
        'max_users',
        'max_storage_mb',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'plan_permission', 'plan_id', 'permission_id');
    }
}
