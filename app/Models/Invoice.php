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
        'id',
        'tenant_id',
        'invoice_number',
        'due_date',
        'invoice_date',
        'status',
        'supplier_id',
        'warehouse_id',
    ];

    /**
     * 🔗 Relation : une facture appartient à un fournisseur.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * 🔗 Relation : une facture appartient à un entrepôt.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * 🔗 Relation : une facture contient plusieurs lignes (InvoiceItems).
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * 🔗 Relation : une facture appartient à un tenant (multi-entreprise).
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
