<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;

class OrderPayment extends Model
{
	use HasFactory, SoftDeletes;

	public $timestamps = true;


	protected $fillable = [
		'amount',
		'nr_fee',
		'date',
		'payment_status_id',
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
			get: fn() => date('d/m/Y', strtotime($this->date))
		);
	}

	// Scopes
	public function scopeOnlyCompleted(Builder $query): void
	{
		$query->whereHas('paymentStatus', function (Builder $q) {
			$q->where('name', 'Completado');
		});
	}

	/**
	 * Scope para rango de fechas personalizado
	 */
	public function scopeDateRange(Builder $query, string $startDate, ?string $endDate = null): void
	{
		$endDate = $endDate ?? now();
		$query->whereBetween('date', [$startDate, $endDate]);
	}

	// Relationships
	public function paymentStatus(): BelongsTo
	{
		return $this->belongsTo(PaymentStatus::class);
	}

	public function payment(): BelongsTo
	{
		return $this->belongsTo(Payment::class);
	}

	public function order(): BelongsTo
	{
		return $this->belongsTo(Order::class);
	}
}
