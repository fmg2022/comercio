<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferLine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'table',
        'table_id'
    ];

    public function Offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
