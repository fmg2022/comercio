<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'total'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    // This method defines a many-to-many relationship with the Product model
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->using(OrderProduct::class)
            ->withPivot(['quantity', 'price', 'discount'])
            ->withTimestamps();
    }

    /**
     * Calculate the total price of the order based on the products and their pivot data.
     * This method sums up the total price for each product in the order, taking into account
     * the quantity, price, and any discounts applied.
     */
    public function calculateTotal()
    {
        $this->total = $this->products()->sum(function ($product) {
            return ($product->pivot->price * $product->pivot->quantity) - $product->pivot->discount;
        });

        $this->save();
    }
}
