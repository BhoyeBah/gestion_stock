<?php

namespace App\Models;

use App\Traits\HasTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, HasTenant, HasUuid;

    protected $fillable = [
        'contact_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'type',
        'total_invoice',
        'balance',
        'created_at',
        'updated_at',
    ];

    // Types disponibles
    public const TYPE_CLIENT = 'client';
    public const TYPE_SUPPLIER = 'supplier';

    /* =====================
       SCOPES
       ===================== */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeClients($query)
    {
        return $this->scopeType($query, self::TYPE_CLIENT);
    }

    public function scopeSuppliers($query)
    {
        return $this->scopeType($query, self::TYPE_SUPPLIER);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /* =====================
       RELATIONS
       ===================== */

    // Lien avec le contact (client ou fournisseur)
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    // Lignes de facture
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
