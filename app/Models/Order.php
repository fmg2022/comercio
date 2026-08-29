<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasOne};

class Order extends Model
{
	use HasFactory;

	protected $fillable = [
		'date',
		'total',
		'iva',
		'shipping_cost',
		'notes',
		'order_state_id',
		'user_id',
	];

	protected $casts = [
		'date' => 'datetime',
	];

	// Accesores
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

	protected function totalWithIva(): Attribute
	{
		return Attribute::make(
			get: fn() => $this->total + $this->iva
		);
	}

	// Scopes
	/**
	 * Solo órdenes pagadas/compradas
	 */
	#[Scope]
	public function paid(Builder $query): void
	{
		static $paidStateId = null;

		if ($paidStateId === null) {
			$paidStateId = OrderState::whereIn('slug', ['paid', 'completed'])->pluck('id');
		}
		$query->whereIn('order_state_id', $paidStateId);
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

	public function shipping(): HasMany
	{
		return $this->hasMany(Shipping::class);
	}

	public function products(): BelongsToMany
	{
		return $this->belongsToMany(Product::class)
			->using(OrderProduct::class)
			->withPivot(['quantity', 'price', 'discount', 'offer_id', 'offer_template_name', 'offer_type_slug'])
			->withTimestamps();
	}
}
