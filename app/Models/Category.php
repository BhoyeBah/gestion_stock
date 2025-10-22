<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;
use App\Traits\HasUuid;

class Category extends Model
{
    use HasFactory, HasTenant,HasUuid;

    protected $fillable = [
        'name',
        'slug',
        'tenant_id',
    ];
}
