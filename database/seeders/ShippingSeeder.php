<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$orders = Order::query()
			->doesntHave('shipping')
			->with(['orderState:id,slug', 'user.addresses:id,user_id'])
			->get(['id', 'order_state_id', 'date', 'user_id', 'total']);
		$shippingStates = \App\Models\ShippingState::pluck('id', 'slug');

		foreach ($orders as $order) {
			$orderStateSlug = $order->orderState->slug;
			$deliveryMethod = fake()->randomElement(['shipping', 'pickup']);

			if ($deliveryMethod === 'pickup') {
				$addressId = null;
			} else {
				// Suponiendo que todos los usuarios tienen por lo menos una dirección
				$addressId = $order->user->addresses->random()->id;
			}

			$shippingStateSlug = match ($orderStateSlug) {
				'cancelled' => 'cancelled',
				'refunded' => 'failed',
				'completed' => 'delivered',
				'paid' => $this->handleShippingState($order, $deliveryMethod),
				default => 'pending',
			};

			$shippingStateId = $shippingStates[$shippingStateSlug] ?? null;

			$transportista = $shippingStateSlug != 'cancelled'
				? \App\Models\User::where('id', '!=', $order->user_id)->role('logistics')->inRandomOrder()->value('id')
				: null;

			$shippingData = [
				'address_id' => $addressId,
				'transport_user_id' => $transportista,
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

	private function handleShippingState(Order $order, string $method): string
	{
		if ($method === 'pickup') {
			return 'ready_for_pickup';
		}

		$estimatedDeliveryDate = $order->date->copy()->addDays(rand(1, 7));
		return $estimatedDeliveryDate->greaterThan(now()) ? 'in_transit' : 'assigned';
	}
}
