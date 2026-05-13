<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrderPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
        'provider_id',
        'order_purcharse_states_id',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function orderPurchaseState(): BelongsTo
    {
        return $this->belongsTo(OrderPurchaseState::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['quantity', 'purcharse_price', 'suggested_sale_price']);
    }
}
