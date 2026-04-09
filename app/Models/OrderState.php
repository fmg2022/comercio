<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderState extends Model
{
    protected $fillable = [
        'code',
        'description',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
