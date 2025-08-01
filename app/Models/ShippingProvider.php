<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingProvider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
