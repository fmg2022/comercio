<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transport_user_id',
        'shipping_states_id',
        'shipping_rate_id',
        'address_id',
        'tracking_number',
        'shipping_cost',
        'delivery_method',
        'estimated_delivery_date',
        'delivered_at',
        'notes',
        'is_feasible',
    ];

    protected $casts = [
        'estimated_delivery_date' => 'date',
        'delivered_at' => 'datetime',
        'is_feasible' => 'boolean',
    ];

    // Scopes
    /**
     * Solo envíos factibles
     */
    #[Scope]
    public function feasible(Builder $query)
    {
        return $query->where('is_feasible', true);
    }

    // Functions
    /**
     * Asignar usuario de transporte
     * @return void 
     * */
    public function assignTransportUser(User $user)
    {
        if (!$user->hasRole('logistics')) {
            throw new \Exception('El usuario no tiene el rol de Logística.');
        }
        $this->transport_user_id = $user->id;
        $this->save();
    }

    /**
     * Marcar envío como entregado
     * @return void 
     * */
    public function markAsDelivered()
    {
        $this->shipping_states_id = ShippingState::where('slug', 'delivered')->first()->id;
        $this->delivered_at = now();
        $this->save();
    }

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function transportUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transport_user_id');
    }

    public function shippingState(): BelongsTo
    {
        return $this->belongsTo(ShippingState::class, 'shipping_states_id');
    }

    public function shippingRate(): BelongsTo
    {
        return $this->belongsTo(ShippingRate::class, 'shipping_rate_id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
}
