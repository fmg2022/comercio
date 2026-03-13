<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'provider_transaction_id',
        'provider_state',
        'checkout_url',
        'method',
        'amount',
        'paid_at',
    ];

    // Accessors
    public function amountFormated(): Attribute
    {
        return Attribute::make(
            get: fn() => Number::currency($this->amount, 'ARS', 'es_AR')
        );
    }

    public function dateFormated(): Attribute
    {
        return Attribute::make(
            get: fn() => date('d/m/Y', strtotime($this->paid_at))
        );
    }

    // Scopes
    public function scopeOnlyAprobed(Builder $query): void
    {
        $query->whereHas('paymentState', function (Builder $q) {
            $q->where('code', 'APROBADO');
        });
    }

    /**
     * Scope para rango de fechas personalizado
     */
    public function scopeDateRange(Builder $query, string $startDate, ?string $endDate = null): void
    {
        $endDate = $endDate ?? now();
        $query->whereBetween('paid_at', [$startDate, $endDate]);
    }

    // Relationships
    public function paymentState(): BelongsTo
    {
        return $this->belongsTo(PaymentState::class);
    }

    public function paymentProvider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
