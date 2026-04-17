<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfferType extends Model
{
    protected $fillable = [
        'code',
        'description',
    ];

    public function offerTemplates(): HasMany
    {
        return $this->hasMany(OfferTemplate::class);
    }
}
