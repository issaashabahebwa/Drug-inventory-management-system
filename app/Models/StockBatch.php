<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class StockBatch extends Model
{
    protected $fillable = [
        'drug_id',
        'supplier_id',
        'received_by',
        'batch_number',
        'quantity_received',
        'quantity_remaining',
        'manufacture_date',
        'expiry_date',
        'received_date',
        'delivery_note_number',
        'unit_cost',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'manufacture_date'   => 'date',
            'expiry_date'        => 'date',
            'received_date'      => 'date',
            'quantity_received'  => 'integer',
            'quantity_remaining' => 'integer',
            'unit_cost'          => 'decimal:2',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────
    public function drug(): BelongsTo
    {
        return $this->belongsTo(Drug::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function dispensingRecords(): HasMany
    {
        return $this->hasMany(DispensingRecord::class);
    }

    // ── Expiry helpers ────────────────────────────────────────────
    public function getDaysToExpiryAttribute(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->expiry_date, false);
    }

    public function isExpired(): bool
    {
        return $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $days = 90): bool
    {
        return $this->days_to_expiry >= 0 && $this->days_to_expiry <= $days;
    }

    public function getExpiryStatusAttribute(): string
    {
        if ($this->days_to_expiry < 0)  return 'expired';
        if ($this->days_to_expiry <= 30) return 'critical';
        if ($this->days_to_expiry <= 60) return 'warning';
        if ($this->days_to_expiry <= 90) return 'soon';
        return 'ok';
    }

    public function getExpiryColorAttribute(): string
    {
        return match ($this->expiry_status) {
            'expired'  => 'danger',
            'critical' => 'danger',
            'warning'  => 'warning',
            'soon'     => 'info',
            default    => 'success',
        };
    }

    // ── Quantity helpers ──────────────────────────────────────────
    public function getQuantityDispensedAttribute(): int
    {
        return $this->quantity_received - $this->quantity_remaining;
    }

    public function getUsagePercentAttribute(): float
    {
        if ($this->quantity_received === 0) return 0;
        return round(($this->quantity_dispensed / $this->quantity_received) * 100, 1);
    }
}
