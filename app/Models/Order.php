<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\JoinClause;

class Order extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'date',
		'total',
		'order_state_id',
		'user_id',
		'address_id',
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
		$query->join('order_states', function (JoinClause $join) {
			$join->on('orders.order_state_id', '=', 'order_states.id')
				->where('order_states.code', '!=', 'CANCELADO');
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

	public function address(): BelongsTo
	{
		return $this->belongsTo(Address::class);
	}

	public function orderState(): BelongsTo
	{
		return $this->belongsTo(OrderState::class);
	}

	public function shipment(): HasOne
	{
		return $this->hasOne(Shipment::class);
	}

	public function payment(): HasOne
	{
		return $this->hasOne(Payment::class);
	}

	public function products(): BelongsToMany
	{
		return $this->belongsToMany(Product::class)
			->using(OrderProduct::class)
			->withPivot(['quantity', 'price', 'discount'])
			->withTimestamps();
	}
}
