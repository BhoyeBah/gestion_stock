<?php

namespace App\Models;

use App\Traits\HasTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, HasTenant, HasUuid;

    /**
     * Les attributs qui peuvent être remplis en masse.
     */
    protected $fillable = [
        'invoice_id',
        'tenant_id',
        'contact_id',
        'amount_paid',
        'remaining_amount',
        'payment_date',
        'payment_type',
        'payment_source'
    ];


    /**
     * Relation : un paiement appartient à une facture.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Relation : un paiement appartient à un tenant (locataire).
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relation : un paiement appartient à un tenant (locataire).
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
