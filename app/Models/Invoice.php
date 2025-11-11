<?php

namespace App\Models;

use App\Traits\HasTenant;
use App\Traits\HasUuid;
use Carbon\Carbon;
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

    protected $casts = [
        'invoice_date' => 'datetime',
        'due_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    // ✅ Relation avec les paiements de cette facture
    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id', 'id');
    }

    /**
     * Génère un invoice_number unique pour cette facture.
     * Utilisable comme $invoice->generateInvoiceNumber();
     */
    public function generateInvoiceNumber(): string
{
    if ($this->invoice_number) {
        // Si l'utilisateur a déjà défini un numéro, on ne le regénère pas.
        // Mais tu veux ignorer les numéros manuels pour le calcul, pas pour la génération actuelle.
        return $this->invoice_number;
    }

    if (! $this->tenant_id || ! $this->type) {
        throw new \RuntimeException('tenant_id et type requis pour générer invoice_number');
    }

    // Date de facture
    $this->invoice_date = $this->invoice_date ?? now();
    $year = \Carbon\Carbon::parse($this->invoice_date)->format('Y');

    $base = "FAC-{$year}-";

    // Récupère le dernier numéro généré valide de l’année
    $lastInvoice = self::where('tenant_id', $this->tenant_id)
        ->where('type', $this->type)
        ->whereYear('invoice_date', $year)
        ->where('status', '!=', 'draft')
        ->where('invoice_number', 'LIKE', $base . '%') // 🔹 Ignore les numéros manuels
        ->orderByDesc('invoice_number')
        ->first();

    // Extrait la partie numérique (ex: "FAC-2025-000009" => 9)
    $lastNumber = 0;
    if ($lastInvoice && preg_match('/FAC-' . $year . '-(\d+)/', $lastInvoice->invoice_number, $matches)) {
        $lastNumber = (int) $matches[1];
    }

    // Incrémente le numéro
    $next = $lastNumber + 1;

    // Formate le nouveau numéro
    $this->invoice_number = sprintf('%s%06d', $base, $next);

    return $this->invoice_number;
}

}
