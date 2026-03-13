<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Pivot
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'quantity',
        'price',
        'discount'
    ];

    protected function priceFormated(): Attribute
    {
        return Attribute::make(
            get: fn() => number_format($this->price, 2, ',', '.'),
        );
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function subtotal(): string
    {
        return number_format(($this->price * $this->quantity) - $this->discount, 2, ',', '.');
    }
}
