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

    // Functions
    public function attachProduct(string $productId, int $quantity): void
    {
        $this->products()->attach($productId, ['quantity' => $quantity]);
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

    public function updateProduct(string $productId, int $quantity, float $discount = 0): void
    {
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
            ->withPivot(['quantity', 'discount']);
    }
}
