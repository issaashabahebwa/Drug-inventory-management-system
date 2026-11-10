<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcurementRequest extends Model
{
    protected $fillable = [
        'request_number',
        'requested_by',
        'supplier_id',
        'status',
        'request_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'request_date' => 'date',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProcurementRequestItem::class);
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft'     => 'secondary',
            'submitted' => 'primary',
            'approved'  => 'info',
            'fulfilled' => 'success',
            default     => 'secondary',
        };
    }

    // Auto-generate request number
    public static function generateRequestNumber(): string
    {
        $year  = now()->year;
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'PR-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
