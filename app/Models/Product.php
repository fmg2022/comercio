<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'sku',
        'price',
        'stock',
        'description',
    ];

    // Accessors & Mutators
    protected function priceFormated(): Attribute
    {
        return Attribute::make(
            get: fn() => number_format($this->price, 2, ',', '.'),
        );
    }

    public function getCurrentOffer(): ?object
    {
        return $this->belongsToMany(Offer::class)
            ->whereHas('offerState', function ($query) {
                $query->where('code', 'ACTIVA');
            })
            ->first()
            ?->offerTemplate;
    }

    public function getDiscountTotal(int $quantity, int $offerId): float
    {
        $offerT = OfferTemplate::find($offerId);
        $offerType = $offerT->offerType->code;

        if ($offerT) {
            if ($offerType === 'PERCENTAGE') {
                return $this->price * $offerT->pay_qty * $quantity;
            }
            if ($offerType === 'X_FOR_Y') {
                return $this->price * (intdiv($quantity, $offerT->buy_qty) * $offerT->pay_qty);
            }
            if ($offerType === 'FIXED') {
                return $offerT->pay_qty * $quantity;
            }
        }
        return 0;
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class)
            ->withPivot(['quantity']);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
            ->using(OrderProduct::class)
            ->withPivot(['quantity', 'price', 'discount'])
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class)
            ->withTimestamps();
    }

    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(Provider::class)
            ->withPivot('price')
            ->withTimestamps();
    }

    // Functions
    public function activeOffer()
    {
        return $this->offers()->active()->first()?->id;
    }
}
