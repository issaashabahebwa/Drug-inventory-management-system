<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'code',
        'contact_person',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────
    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }

    public function procurementRequests(): HasMany
    {
        return $this->hasMany(ProcurementRequest::class);
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function getTotalDeliveriesAttribute(): int
    {
        return $this->stockBatches()->count();
    }
}
