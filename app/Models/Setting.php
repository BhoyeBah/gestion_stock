<?php

namespace App\Models;

use App\Traits\HasTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory, HasTenant, HasUuid;
    protected $fillable = [
        'tenant_id',
        'currency',
        'tva',
    ];
}
