<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends Pivot
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'quantity',
        'price',
        'discount',
        'offer_template_id',
        'offer_type_code',
    ];

    // Accessors
    protected function getSubtotal(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->price * $this->quantity - $this->discount,
        );
    }

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Functions
    public function subtotal(): string
    {
        return number_format(($this->price * $this->quantity) - $this->discount, 2, ',', '.');
    }

    public function offerName(): string
    {
        return OfferTemplate::where('id', $this->offer_template_id)->first()?->name ?? 'Sin descuento';
    }
}
