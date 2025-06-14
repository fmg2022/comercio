<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'mark',
        'image',
        'price',
        'quantity',
        'description',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cart(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class)->as('cartProduct')
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
            ->withPivot(['quantity', 'price', 'discount'])
            ->withTimestamps();
    }

    public function wishlistUser(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->as('wishList')
            ->withPivot(['added_at'])
            ->withTimestamps();
    }

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class)
            ->withPivot(['initial_date', 'expiration_date', 'discount_value'])
            ->withTimestamps();
    }
}
