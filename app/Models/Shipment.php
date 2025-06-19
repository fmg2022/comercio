<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tracking_number',
        'shipped_at',
        'delivered_at',
    ];

    public function shippingProvider()
    {
        return $this->belongsTo(ShippingProvider::class);
    }
}
