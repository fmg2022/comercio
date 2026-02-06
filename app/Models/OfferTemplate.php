<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OfferTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'offer_type_id',
    ];

    public function offerType(): BelongsTo
    {
        return $this->belongsTo(OfferType::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function percentages(): HasOne
    {
        return $this->hasOne(OfferPercentage::class);
    }

    public function xforys(): HasOne
    {
        return $this->hasOne(OfferXfory::class);
    }
}
