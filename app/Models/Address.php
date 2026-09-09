<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'street',
        'intersection',
        'locality',
        'province',
        'postal_code',
        'latitude',
        'longitude',
        'is_default',
    ];

    protected $hidden = [
        'longitude',
        'latitude',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Functions
    /**
     * Método auxiliar para establecer esta dirección como la predeterminada.
     * Desmarca cualquier otra dirección predeterminada del mismo usuario.
     */
    public function setAsDefault(): void
    {
        $this->user->addresses()
            ->where('is_default', true)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }

    public function fullAddress(): string
    {

        return $this->street
            . ($this->intersection ? ', ' . $this->intersection : '')
            . ', ' . $this->locality
            . ', ' . $this->province
            . ' ' . $this->postal_code;;
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
