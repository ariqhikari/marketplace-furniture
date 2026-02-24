<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * User Model
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'role',
        'store_name',
        'store_description',
        'store_logo',
        'address',
        'city',
        'province',
        'postal_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /** Order items where this user is the seller */
    public function sellerOrderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'seller_id');
    }


    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    public function isSeller(): bool
    {
        return $this->role === 'SELLER';
    }

    public function isUser(): bool
    {
        return $this->role === 'USER';
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->province,
            $this->postal_code,
        ]);

        return implode(', ', $parts) ?: '-';
    }
}
