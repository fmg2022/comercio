<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferXfory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'buy_qty',
        'pay_qty',
        'offer_template_id',
    ];

    public function offerTemplate(): BelongsTo
    {
        return $this->belongsTo(OfferTemplate::class);
    }
}
