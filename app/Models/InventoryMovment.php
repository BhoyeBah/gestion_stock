<?php

namespace App\Models;

use App\Traits\HasTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'id',
        'invoice_item_id',
        'invoice_id',
        'batch_id',
        'product_id',
        'quantity',
        'reason',
    ];

    /**
     * Relation vers l’article de facture (InvoiceItem)
     */
    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    /**
     * Relation vers la facture (Invoice)
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Relation vers le lot (Batch)
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Relation vers le produit (Product)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
