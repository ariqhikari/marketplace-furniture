<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Category Model
 */
class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── ACCESSORS ──

    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        return 'https://placehold.co/400x400/e9ecef/495057?text=' . urlencode($this->name);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
