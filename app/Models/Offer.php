<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'type_discount',
        'discount',
        'initial_date',
        'expiration_date'
    ];

    public function OfferConection(): BelongsTo
    {
        return $this->belongsTo(OfferConection::class);
    }
}
