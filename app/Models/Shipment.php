<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'carrier_name',
        'tracking_number',
        'shipping_cost',
        'shipped_at',
        'delivered_at',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(ShipmentState::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
