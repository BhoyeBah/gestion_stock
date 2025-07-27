<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'tenant_id',
    ];

    /**
     * Relation avec le tenant (entreprise).
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope pour ne récupérer que les rôles d'une entreprise donnée.
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Vérifie si le rôle appartient à la plateforme (pas lié à un tenant).
     */
    public function isPlatformRole()
    {
        return $this->tenant->slug === "platform";
    }
}
