<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfferTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'offer_type_id',
        'buy_qty',
        'pay_qty',
    ];

    public function offerType(): BelongsTo
    {
        return $this->belongsTo(OfferType::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }
}
