<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'mark',
        'description',
        'image',
        'price',
        'quantity'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cart(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class)->as('cartProduct')->withPivot('quantity');
    }

    public function orderLines(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->as('orderLine')->withPivot(['quantity', 'price']);
    }

    public function wishlistUser(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->as('wishList');
    }
}
