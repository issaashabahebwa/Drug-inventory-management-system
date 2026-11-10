<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DispensingRecord extends Model
{
    protected $fillable = [
        'drug_id',
        'stock_batch_id',
        'dispensed_by',
        'patient_reference',
        'patient_name',
        'quantity_dispensed',
        'dispensed_date',
        'dispensed_time',
        'diagnosis',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'dispensed_date'      => 'date',
            'quantity_dispensed'  => 'integer',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────
    public function drug(): BelongsTo
    {
        return $this->belongsTo(Drug::class);
    }

    public function stockBatch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class);
    }

    public function dispensedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dispensed_by');
    }
}
