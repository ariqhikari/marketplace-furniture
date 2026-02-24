<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Bank Model — daftar rekening bank untuk pembayaran transfer
 */
class Bank extends Model
{
    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ── RELATIONSHIPS ──

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // ── SCOPES ──

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
