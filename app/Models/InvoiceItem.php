<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'invoice_id',
        'quantity',
        'unit_price',
        'discount',
        'total_line',
    ];

    /* =====================
     RELATIONS
     ===================== */

    // Lien avec la facture
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Lien avec le produit
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Lien avec l'entrepôt
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
