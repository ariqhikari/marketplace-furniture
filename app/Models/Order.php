<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Order Model
 */
class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'total_price',
        'status',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'bank_id',
        'payment_proof',
        'notes',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── SCOPES ──

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForSeller($query, int $sellerId)
    {
        return $query->whereHas('orderItems', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        });
    }


    public function getGrandTotalAttribute(): float
    {
        return $this->orderItems->sum(function ($item) {
            return ($item->price ?? 0) * ($item->quantity ?? 0);
        });
    }

    public function getFormattedTotalAttribute(): string
    {
        return format_rupiah($this->total_price);
    }

    /**
     * Status badge color for Bootstrap
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'PENDING'    => 'warning',
            'PROCESSING' => 'info',
            'SHIPPED'    => 'primary',
            'DELIVERED'  => 'success',
            'CANCELLED'  => 'danger',
            default      => 'secondary',
        };
    }
}
