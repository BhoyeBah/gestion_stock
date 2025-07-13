<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'amount_paid',
        'payment_method',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // 🔗 Relations

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // 🔎 Helpers

    public function isExpired(): bool
    {
        return $this->ends_at->isPast();
    }

    public function isCurrentlyActive(): bool
    {
        return $this->is_active && now()->between($this->starts_at, $this->ends_at);
    }
}
