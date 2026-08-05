<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'offer_state_id',
        'offer_template_id',
    ];

    public $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Scopes
    public function scopeActive(Builder $query): void
    {
        $query->whereHas('offerState', fn($query) => $query->where('slug', 'active'))
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    // Relationships
    public function offerState(): BelongsTo
    {
        return $this->belongsTo(OfferState::class);
    }

    public function offerTemplate(): BelongsTo
    {
        return $this->belongsTo(OfferTemplate::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withTimestamps();
    }
}
