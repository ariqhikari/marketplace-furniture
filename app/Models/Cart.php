<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Cart Model
 */
class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    public function getSubtotalAttribute(): float
    {
        return $this->product->price * $this->quantity;
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return format_rupiah($this->subtotal);
    }
}
