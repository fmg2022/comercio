<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentState extends Model
{
    protected $fillable = ['code', 'description'];

    // Accessors
    public function codeFormated(): Attribute
    {
        return Attribute::make(
            get: fn() => str_replace('_', ' ', $this->code)
        );
    }

    // Relationships
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
