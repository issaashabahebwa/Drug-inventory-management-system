<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Drug extends Model
{
    protected $fillable = [
        'name',
        'brand_name',
        'category',
        'formulation',
        'strength',
        'unit_of_measure',
        'reorder_level',
        'total_quantity',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_active'       => 'boolean',
            'reorder_level'   => 'integer',
            'total_quantity'  => 'integer',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────
    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }

    public function activeBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class)
                    ->where('status', 'active')
                    ->where('quantity_remaining', '>', 0)
                    ->orderBy('expiry_date');   // FEFO order
    }

    public function dispensingRecords(): HasMany
    {
        return $this->hasMany(DispensingRecord::class);
    }

    public function procurementRequestItems(): HasMany
    {
        return $this->hasMany(ProcurementRequestItem::class);
    }

    // ── Stock status helpers ──────────────────────────────────────
    public function getStockStatusAttribute(): string
    {
        if ($this->total_quantity <= 0) {
            return 'out_of_stock';
        }
        if ($this->total_quantity <= $this->reorder_level) {
            return 'low';
        }
        if ($this->total_quantity <= ($this->reorder_level * 1.5)) {
            return 'moderate';
        }
        return 'adequate';
    }

    public function getStockStatusLabelAttribute(): string
    {
        return match ($this->stock_status) {
            'out_of_stock' => 'Out of Stock',
            'low'          => 'Low Stock',
            'moderate'     => 'Moderate',
            'adequate'     => 'Adequate',
            default        => 'Unknown',
        };
    }

    public function getStockStatusColorAttribute(): string
    {
        return match ($this->stock_status) {
            'out_of_stock' => 'danger',
            'low'          => 'danger',
            'moderate'     => 'warning',
            'adequate'     => 'success',
            default        => 'secondary',
        };
    }

    public function isLowStock(): bool
    {
        return $this->total_quantity <= $this->reorder_level;
    }

    // ── Recalculate total_quantity from active batches ────────────
    public function recalculateTotalQuantity(): void
    {
        $total = $this->stockBatches()
                      ->whereIn('status', ['active'])
                      ->sum('quantity_remaining');

        $this->update(['total_quantity' => $total]);
    }

    // ── Nearest-expiry active batch (FEFO) ────────────────────────
    public function getNextFefoBatchAttribute(): ?StockBatch
    {
        return $this->activeBatches()->first();
    }

    // ── Category list (EMHS Uganda) ───────────────────────────────
    public static function categories(): array
    {
        return [
            'Analgesics & Anti-Pyretics',
            'Antibiotics & Anti-Infectives',
            'Antifungals',
            'Antivirals & ARVs',
            'Antimalarials',
            'Antihypertensives',
            'Antidiabetics',
            'Vitamins & Nutritional Supplements',
            'Gastrointestinal Drugs',
            'Respiratory Drugs',
            'Dermatological Preparations',
            'Eye & Ear Preparations',
            'Obstetric & Gynaecological',
            'Vaccines & Immunologicals',
            'Surgical & Anaesthetic Drugs',
            'Other',
        ];
    }

    public static function formulations(): array
    {
        return ['tablet', 'capsule', 'syrup', 'injection', 'cream', 'drops', 'suppository', 'inhaler', 'other'];
    }
}
