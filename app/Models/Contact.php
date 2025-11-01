<?php

namespace App\Models;

use App\Traits\HasTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory, HasTenant, HasUuid;

    // Types disponibles
    public const TYPE_CLIENT = 'client';

    public const TYPE_SUPPLIER = 'supplier';

    protected $fillable = [

        'fullname',
        'phone_number',
        'address',
        'type',
    ];

    /* =====================
     SCOPES
     ===================== */

    /**
     * Scope générique pour filtrer par type.
     * Usage: Contact::type(Contact::TYPE_CLIENT)->get();
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope helper pour les clients.
     * Usage: Contact::clients()->get();
     */
    public function scopeClients($query)
    {
        return $this->scopeType($query, self::TYPE_CLIENT);
    }

    /**
     * Scope helper pour les fournisseurs.
     * Usage: Contact::suppliers()->get();
     */
    public function scopeSuppliers($query)
    {
        return $this->scopeType($query, self::TYPE_SUPPLIER);
    }

    /**
     * Scope pour is_active = true
     * Usage: Contact::active()->get();
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /* =====================
       RELATIONS
       ===================== */

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
