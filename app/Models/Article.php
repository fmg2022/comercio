<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'table',
        'table_id'
    ];

    public function Offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class)->as('details')->withPivot(['initial_date', 'expiration_date']);
    }
}
