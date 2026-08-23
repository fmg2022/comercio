<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = ['min_distance', 'max_distance', 'cost', 'is_active', 'valid_from', 'valid_until'];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function shippings()
    {
        return $this->hasMany(Shipping::class);
    }
}
