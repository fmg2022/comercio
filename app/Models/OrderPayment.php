<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPayment extends Model
{
	use HasFactory, SoftDeletes;

	public $timestamps = true;


	protected $fillable = [
		'amount',
		'nro_fee',
		'date',
		'payment_status_id',
	];

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
