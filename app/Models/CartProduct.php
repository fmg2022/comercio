<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CartProduct extends Pivot
{
    protected $table = 'cart_product';

    protected $touches = ['product'];

    protected $fillable = [
        'quantity',
        'discount',
    ];

    // Accessors
    public function subtotal(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->product->price * $this->quantity - $this->discount,
        );
    }

    // Relationships
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
