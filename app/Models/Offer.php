<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'type_discount',
        'discount'
    ];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class)->as('details')->withPivot(['initial_date', 'expiration_date']);
    }
}
