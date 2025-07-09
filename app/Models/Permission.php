<?php
namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
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
