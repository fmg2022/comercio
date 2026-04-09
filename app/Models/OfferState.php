<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfferState extends Model
{
    protected $fillable = [
        'code',
        'description',
    ];

    public function Offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }
}
