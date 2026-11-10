<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementRequestItem extends Model
{
    protected $fillable = [
        'procurement_request_id',
        'drug_id',
        'quantity_requested',
        'current_stock',
        'reorder_level',
        'justification',
    ];

    protected function casts(): array
    {
        return [
            'quantity_requested' => 'integer',
            'current_stock'      => 'integer',
            'reorder_level'      => 'integer',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────
    public function procurementRequest(): BelongsTo
    {
        return $this->belongsTo(ProcurementRequest::class);
    }

    public function drug(): BelongsTo
    {
        return $this->belongsTo(Drug::class);
    }
}
