<?php

namespace Database\Seeders;

use App\Enums\DeliveryMethod;
use App\Models\{Order, Shipping, ShippingState, User};
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$orders = Order::query()
			->where('delivery_method', DeliveryMethod::Shipping)
			->doesntHave('shipping')
			->with([
				'orderState:id,slug',
				'user.addresses:id,user_id',
			])
			->get(['id', 'order_state_id', 'date', 'user_id', 'total']);
		$shippingStates = ShippingState::pluck('id', 'slug');
		$transportistas = User::role('logistics')
			->pluck('id')
			->all();

		foreach ($orders as $order) {
			$orderStateSlug = $order->orderState->slug;
			$addressId = $order->user->addresses->random()?->id;
			$estimatedDeliveryDate = $order->date->copy()
				->addHours(rand(4, 10));

			$shippingStateSlug = match ($orderStateSlug) {
				'cancelled' => 'cancelled',
				'refunded' => 'failed',
				'completed' => 'delivered',
				'paid' => $estimatedDeliveryDate->greaterThan(now())
					? 'in_transit'
					: 'assigned',
				default => 'pending',
			};

			$transportista = null;

			if (in_array($shippingStateSlug, ['assigned', 'in_transit', 'delivered', 'failed'])) {
				$transportista = collect($transportistas)
					->reject(fn($id) => $id == $order->user_id)
					->random();
			}

			if ($transportista !== null && $shippingStateSlug === 'pending') {
				$shippingStateSlug = 'assigned';
			}

			$shippingData = [
				'address_id' => $addressId,
				'transport_user_id' => $transportista,
				'shipping_state_id' => $shippingStates[$shippingStateSlug] ?? null,
				'estimated_delivery_date' => $estimatedDeliveryDate,
			];

			if (in_array($shippingStateSlug, ['cancelled', 'failed'])) {
				$shippingData['tracking_number'] = null;
				$shippingData['cost'] = 0;
				$shippingData['notes'] = $shippingStateSlug === 'cancelled'
					? 'Cancelado por el cliente'
					: 'No es posible enviar, ocurrió un problema';

				$shippingData['distance_km'] = fake()->randomFloat(2, 10.01, 14.3);
			} else {
				$shippingData['delivered_to'] = $shippingStateSlug === 'delivered'
					? $estimatedDeliveryDate->copy()->addHours(rand(-2, 24))
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
