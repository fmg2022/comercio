<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingState extends Model
{
    protected $fillable = ['name', 'slug'];

    public function shippings()
    {
        return $this->hasMany(Shipping::class);
    }
}
