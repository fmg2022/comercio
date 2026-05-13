<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderPurchaseState extends Model
{
    protected $fillable = [
        'code',
        'description',
        'active',
    ];

    public function orderPurchases(): HasMany
    {
        return $this->hasMany(OrderPurchase::class);
    }
}
