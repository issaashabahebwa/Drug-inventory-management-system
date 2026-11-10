<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Role constants
    const ROLE_ADMIN          = 'administrator';
    const ROLE_STORES         = 'stores_in_charge';
    const ROLE_PHARMACIST     = 'pharmacy_technician';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ── Avatar URL helper ─────────────────────────────────────────
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    // ── Role helpers ──────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStoresInCharge(): bool
    {
        return $this->role === self::ROLE_STORES;
    }

    public function isPharmacyTechnician(): bool
    {
        return $this->role === self::ROLE_PHARMACIST;
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN      => 'Administrator',
            self::ROLE_STORES     => 'Stores In-Charge',
            self::ROLE_PHARMACIST => 'Pharmacy Technician',
            default               => ucfirst($this->role),
        };
    }

    // ── Relationships ─────────────────────────────────────────────
    public function stockBatchesReceived(): HasMany
    {
        return $this->hasMany(StockBatch::class, 'received_by');
    }

    public function dispensingRecords(): HasMany
    {
        return $this->hasMany(DispensingRecord::class, 'dispensed_by');
    }

    public function procurementRequests(): HasMany
    {
        return $this->hasMany(ProcurementRequest::class, 'requested_by');
    }
}
