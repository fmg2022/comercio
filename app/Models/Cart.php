<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
    ];

    // Accessors
    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->products()
                ->selectRaw('SUM(products.price * cart_product.quantity - cart_product.discount) as total')
                ->value('total') ?? 0
        );
    }

    protected function quantity(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->products()->sum('cart_product.quantity')
        );
    }

    // Functions
    public function attachProduct(int $productId, int $quantity): void
    {
        $product = Product::findOrFail($productId);
        $offerTemplate = Offer::find($product->activeOffer())?->active()?->offerTemplate;
        $discount = $offerTemplate
            ? $product->getDiscountTotal(
                $quantity,
                $offerTemplate->buy_qty,
                $offerTemplate->pay_qty,
                $offerTemplate->offerType->slug
            )
            : 0;
        $this->products()->attach($productId, ['quantity' => $quantity, 'discount' => $discount]);
        $this->touch();
    }

    public function detachProduct(string | array $productId): void
    {
        if (empty($productId)) {
            $this->products()->detach();
        } else {
            $this->products()->detach($productId);
        }
        $this->touch();
    }

    public function updateProduct(int $productId, int $quantity): void
    {
        $product = $this->products()->find($productId);
        $offerTemplate = Offer::find($product->activeOffer())?->active()?->offerTemplate;
        $discount = $offerTemplate
            ? $product->getDiscountTotal(
                $quantity,
                $offerTemplate->buy_qty,
                $offerTemplate->pay_qty,
                $offerTemplate->offerType->slug
            )
            : 0;

        $this->products()->updateExistingPivot($productId, ['quantity' => $quantity, 'discount' => $discount]);
        $this->touch();
    }

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->using(CartProduct::class)
            ->withPivot(['quantity', 'discount']);
    }
}
