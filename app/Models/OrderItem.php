<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * OrderItem Model
 */
class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'seller_id',
        'quantity',
        'price',
        'subtotal',
        'status',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // ── ACCESSORS ──

    public function getFormattedSubtotalAttribute(): string
    {
        return format_rupiah($this->subtotal);
    }

    public function getFormattedPriceAttribute(): string
    {
        return format_rupiah($this->price);
    }

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
