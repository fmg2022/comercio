<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentProvider extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'active', 'default_currency'];

    // Relationships
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
