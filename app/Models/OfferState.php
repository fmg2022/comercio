<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfferState extends Model
{
    protected $fillable = ['slug', 'name'];

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }
}
