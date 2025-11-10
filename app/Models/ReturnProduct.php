<?php

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnProduct extends Model
{
    use HasFactory, HasTenant, HasUuids;

    protected $fillable = [
        'invoice_item_id',
        'inventory_movement_id',
        'quantity',
        'motif',
    ];
    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    public function inventoryMovement()
    {
        return $this->belongsTo(InventoryMovement::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

}
