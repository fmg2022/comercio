<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'description',
    ];

    public function Offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }
}
