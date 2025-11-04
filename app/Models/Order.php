<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\JoinClause;

class Order extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'date',
		'total',
		'order_status_id',
	];

	// Accesores
	protected function totalFormated(): Attribute
	{
		return Attribute::make(
			get: fn() => number_format($this->total, 2, ',', '.'),
		);
	}

	protected function firstPayment(): Attribute
	{
		return Attribute::make(
			get: fn() => $this->payments->first() ?? 'Sin pago',
		);
	}

	// Scopes
	/**
	 * Scope para órdenes no canceladas
	 */
	public function scopeNotCanceled(Builder $query): void
	{
		$query->join('order_statuses', function (JoinClause $join) {
			$join->on('orders.order_status_id', '=', 'order_statuses.id')
				->where('order_statuses.name', '!=', 'Cancelado');
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

	// Relaciones
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function orderStatus(): BelongsTo
	{
		return $this->belongsTo(OrderStatus::class);
	}

	public function offer(): BelongsTo
	{
		return $this->belongsTo(Offer::class);
	}

	public function shipment(): BelongsTo
	{
		return $this->belongsTo(Shipment::class);
	}

	public function payments(): HasMany
	{
		return $this->hasMany(OrderPayment::class);
	}

	public function products(): BelongsToMany
	{
		return $this->belongsToMany(Product::class)
			->using(OrderProduct::class)
			->withPivot(['quantity', 'price', 'discount'])
			->withTimestamps();
	}

	public function getPaymentName(): string
	{
		return $this->first_payment instanceof OrderPayment
			? $this->first_payment->payment->name
			: 'Sin Pago';
	}
}
