<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Product Model
 */
class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'material',
        'color',
        'dimensions',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'weight'      => 'decimal:2',
        'is_active'   => 'boolean',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     */
    public function scopeSearch($query, ?string $keyword)
    {
        if (!$keyword) return $query;

        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%")
                ->orWhere('material', 'like', "%{$keyword}%");
        });
    }


    public function getPrimaryImageAttribute(): ?ProductImage
    {
        return $this->productImages->first();
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->primary_image) {
            return asset('storage/' . $this->primary_image->image_path);
        }
        return 'https://placehold.co/400x400/e9ecef/495057?text=No+Image';
    }

    /**
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }


    public function isAvailable(int $requestedQty = 1): bool
    {
        return $this->is_active && $this->stock >= $requestedQty;
    }
}
