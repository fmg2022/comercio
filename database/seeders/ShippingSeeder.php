<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = \App\Models\Order::query()
            ->doesntHave('shipping')
            ->with('orderState:id,slug')
            ->get(['id', 'order_state_id', 'date', 'user_id']);
        $shippingStates = \App\Models\ShippingState::pluck('id', 'slug');

        foreach ($orders as $order) {
            $orderStateSlug = $order->orderState->slug;

            if ($orderStateSlug === 'cancelled') {
                $shippingStateSlug = 'cancelled';
            } elseif ($orderStateSlug === 'refunded') {
                $shippingStateSlug = 'failed';
            } elseif (in_array($orderStateSlug, ['paid', 'completed'])) {
                if ($orderStateSlug === 'completed') {
                    $shippingStateSlug = 'delivered';
                } else {
                    $estimatedDeliveryDate = fake()->dateTimeBetween('+1 day', '+7 days');
                    $shippingStateSlug = ($estimatedDeliveryDate > now()) ? 'in_transit' : 'assigned';
                }
            } else {
                $shippingStateSlug = 'pending';
            }

            $shippingStateId = $shippingStates[$shippingStateSlug] ?? null;

            $transportista = $shippingStateSlug != 'cancelled'
                ? \App\Models\User::where('id', '!=', $order->user_id)->role('logistics')->inRandomOrder()->first()
                : null;

            $shippingData = [
                'transport_user_id' => $transportista?->id,
                'shipping_states_id' => $shippingStateId,
                'is_feasible' => true,
                'estimated_delivery_date' => null,
            ];

            if ($shippingStateSlug === 'cancelled') {
                $shippingData['tracking_number'] = null;
                $shippingData['shipping_cost'] = 0;
                $shippingData['notes'] = 'Cancelado por el cliente';
                $shippingData['is_feasible'] = false;
            } elseif ($shippingStateSlug === 'failed') {
                $shippingData['tracking_number'] = null;
                $shippingData['shipping_cost'] = 0;
                $shippingData['notes'] = 'No es posible enviar, distancia excedida';
                $shippingData['is_feasible'] = false;
            } else {
                $shippingData['notes'] = fake()->optional()->sentence;
                $shippingData['delivered_at'] = $shippingStateSlug === 'delivered'
                    ? $order->date->addDays(rand(1, 7))
                    : null;
                $shippingData['estimated_delivery_date'] = $shippingStateSlug === 'delivered' ?
                    $shippingData['delivered_at']->addDays(rand(-2, 2))
                    : null;
            }

            $shipping = \App\Models\Shipping::factory()
                ->for($order)
                ->state($shippingData)
                ->create();

            $order->update([
                'shipping_cost' => $shipping->shipping_cost,
                'total' => $order->total + $shipping->shipping_cost,
            ]);
        }
    }
}
