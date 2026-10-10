<?php

namespace Database\Seeders;

use App\Enums\DeliveryMethod;
use App\Models\{Order, Shipping, User};
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Ordenes que no tienen envíos ni son del tipo de retiro de local
		$orders = Order::query()
			->where('delivery_method', DeliveryMethod::Shipping)
			->doesntHave('shipping')
			->with(['orderState:id,slug', 'user.addresses:id,user_id'])
			->get(['id', 'order_state_id', 'date', 'user_id', 'total']);
		$shippingStates = \App\Models\ShippingState::pluck('id', 'slug');

		foreach ($orders as $order) {
			$orderStateSlug = $order->orderState->slug;
			$addressId = $order->user->addresses->random()->id;
			$estimatedDeliveryDate = $order->date->copy()->addDays(rand(1, 7));

			$shippingStateSlug = match ($orderStateSlug) {
				'cancelled' => 'cancelled',
				'refunded' => 'failed',
				'completed' => 'delivered',
				'paid' => $estimatedDeliveryDate->greaterThan(now()) ? 'in_transit' : 'assigned',
				default => 'pending',
			};

			$shippingStateId = $shippingStates[$shippingStateSlug] ?? null;

			$transportista = $shippingStateSlug != 'cancelled'
				? User::where('id', '!=', $order->user_id)->role('logistics')->inRandomOrder()->value('id')
				: null;

			$shippingData = [
				'address_id' => $addressId,
				'transport_user_id' => $transportista,
				'shipping_states_id' => $shippingStateId,
				'estimated_delivery_date' => $estimatedDeliveryDate,
			];

			if ($shippingStateSlug === 'cancelled' || $shippingStateSlug === 'failed') {
				$shippingData['tracking_number'] = null;
				$shippingData['cost'] = 0;
				$shippingData['notes'] = $shippingStateSlug === 'cancelled' ? 'Cancelado por el cliente' : 'No es posible enviar, ocurrió un problema';
				$shippingData['distance_km'] = fake()->randomFloat(2, 10.01, 14.3);
			} else {
				$shippingData['delivered_to'] = $shippingStateSlug === 'delivered'
					? $estimatedDeliveryDate->copy()->addHours(rand(-40, 48))
					: null;
			}

			$shipping = Shipping::factory()
				->for($order)
				->state($shippingData)
				->create();

			$order->update([
				'shipping_cost' => $shipping->cost,
				'total' => $order->total + $shipping->cost,
			]);
		}
	}
}
