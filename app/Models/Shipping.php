<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transport_user_id',
        'shipping_state_id',
        'shipping_rate_id',
        'address_id',
        'tracking_number',
        'distance_km',
        'cost',
        'estimated_delivery_date',
        'delivered_to',
        'notes',
    ];

    protected $casts = [
        'estimated_delivery_date' => 'datetime',
        'delivered_to' => 'datetime',
    ];

    // Functions
    /**
     * Asignar usuario de transporte
     * @return void 
     * */
    public function assignTransportUser(User $user): void
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
        $this->shipping_state_id = ShippingState::where('slug', 'delivered')->first()->id;
        $this->delivered_to = now();
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
        return $this->belongsTo(ShippingState::class, 'shipping_state_id');
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
