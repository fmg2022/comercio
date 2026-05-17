<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
    ];

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

    public function updateProduct(string $productId, int $quantity): void
    {
        $this->products()->updateExistingPivot($productId, ['quantity' => $quantity]);
        $this->touch();
    }

    public function totalFormated(): string
    {
        $total = 0;
        $products = $this->products()->get();
        foreach ($products as $product) {
            $total += $product->price * $product->pivot->quantity;
        }

        return number_format($total, 2, ',', '.');
    }

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['quantity']);
    }
}
