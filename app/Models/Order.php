<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\JoinClause;

class Order extends Model
{
	use HasFactory;

	protected $fillable = [
		'date',
		'total',
		'iva',
		'notes',
		'address',
		'order_state_id',
		'user_id',
	];

	protected $casts = [
		'date' => 'datetime',
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

	protected function totalProducts(): Attribute
	{
		return Attribute::make(
			get: fn() => $this->products->sum('pivot.quantity')
		);
	}

	protected function subTotal(): Attribute
	{
		return Attribute::make(
			get: fn() => $this->products->sum(fn($product) => $product->pivot->price * $product->pivot->quantity)
		);
	}

	protected function discount(): Attribute
	{
		return Attribute::make(
			get: fn() => $this->products->sum('pivot.discount')
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

	public function orderState(): BelongsTo
	{
		return $this->belongsTo(OrderState::class);
	}

	public function payment(): HasOne
	{
		return $this->hasOne(Payment::class);
	}

	public function products(): BelongsToMany
	{
		return $this->belongsToMany(Product::class)
			->using(OrderProduct::class)
			->withPivot(['quantity', 'price', 'discount', 'offer_template_id', 'offer_type_code'])
			->withTimestamps();
	}
}
