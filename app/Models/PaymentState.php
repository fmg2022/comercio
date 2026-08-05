<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentState extends Model
{
    protected $fillable = ['slug', 'name'];

    // Relationships
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
