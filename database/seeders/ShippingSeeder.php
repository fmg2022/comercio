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
                ? \App\Models\User::where('role', 'logistics')->where('id', '!=', $order->user_id)->inRandomOrder()->first()
                : null;

            $shippingData = [
                'transport_user_id' => $transportista?->id,
                'shipping_states_id' => $shippingStateId,
                'is_feasible' => true,
                'estimated_delivery_date' => null,
            ];

            if ($shippingStateSlug === 'cancelled') {
                $shippingData['traking_number'] = null;
                $shippingData['shipping_cost'] = 0;
                $shippingData['notes'] = 'Cancelado por el cliente';
                $shippingData['is_feasible'] = false;
            } else {
                $shippingData['notes'] = fake()->optional()->sentence;
                $shippingData['delivered_at'] = $shippingStateSlug === 'delivered'
                    ? $order->date->addDays(rand(1, 7))
                    : null;
                $shippingData['estimated_delivery_date'] = $shippingData['delivered_at']->addDays(rand(-2, 2));
            }

            \App\Models\Shipping::factory()
                ->for($order)
                ->state($shippingData)
                ->create();
        }
    }
}
