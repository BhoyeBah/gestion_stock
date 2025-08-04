<?php
namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Traits\HasUuid;

class Permission extends SpatiePermission
{
    use HasUuid;
    
    protected $fillable = [
        'name',
        'guard_name',
        'description',
    ];

    public function plans()
    {
        return $this->belongsToMany(\App\Models\Plan::class, 'plan_permission');
    }

}
