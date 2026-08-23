<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\UserSessionHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$taxRate = (float) config('commerce.tax_rate', 21) / 100;

		$products = DB::table('products')
			->select(['id', 'price'])
			->get();

		$offersByProduct = DB::table('offer_product')
			->join('offers', 'offer_product.offer_id', '=', 'offers.id')
			->join('offer_templates', 'offers.offer_template_id', '=', 'offer_templates.id')
			->join('offer_types', 'offer_templates.offer_type_id', '=', 'offer_types.id')
			->select([
				'offer_product.product_id',
				'offer_product.offer_id',
				'offer_types.slug',
				'offer_templates.pay_qty',
				'offer_templates.buy_qty',
				'offer_templates.name',
				'offers.start_date',
				'offers.end_date',
			])
			->get()
			->groupBy('product_id');

		Order::factory(1200)->create()->each(function ($order) use ($offersByProduct, $products, $taxRate) {
			UserSessionHistory::factory()->create([
				'user_id' => $order->user_id,
				'login_at' => $order->date->copy()->subSeconds(rand(360, 900)),
				'logout_at' => $order->date->copy()->addSeconds(rand(180, 900)),
				'last_activity' => $order->date->copy()->subSeconds(rand(60, 180)),
				'is_active' => false,
			]);

			if ($products->isEmpty()) {
				return;
			}

			$productCount = min(
				rand(1, 15),
				$products->count()
			);

			$randomProducts = $products->random($productCount);

			$subtotal = 0;
			$discountTotal = 0;
			$orderProducts = [];

			foreach ($randomProducts as $product) {
				$qty = rand(1, 6);
				$unitPrice = $product->price;
				$lineSubtotal = $qty * $unitPrice;
				$subtotal += $lineSubtotal;

				$productOffer = $offersByProduct->get($product->id, collect());
				$validOffers = $productOffer->filter(fn($offer) => $offer->start_date <= $order->date && $offer->end_date >= $order->date);

				$offer = $validOffers->isNotEmpty()
					? $validOffers->random()
					: null;

				$discount = 0;
				$offerId = '';
				$offerName = '';
				$offerTypeSlug = '';

				if ($offer) {
					$discount = round(
						$this->calculateDiscountAmount(
							offerType: $offer->slug,
							quantity: $qty,
							unitPrice: $unitPrice,
							discountValue: $offer->pay_qty,
							buyQuantity: $offer->buy_qty ?? 0
						),
						2
					);
					$discountTotal += $discount;

					$offerId = $offer->offer_id;
					$offerName = $offer->name;
					$offerTypeSlug = $offer->slug;
				}

				$orderProducts[$product->id] = [
					'quantity' => $qty,
					'price' => $unitPrice,
					'discount' => $discount,
					'offer_id' => $offerId,
					'offer_template_name' => $offerName,
					'offer_type_slug' => $offerTypeSlug,
				];
			}

			// Cálculo de total e impuestos
			$base = $subtotal - $discountTotal;
			$iva = round($base * $taxRate, 2);
			$total = $base + $iva;

			// Adjuntar productos y actualizar la orden
			$order->products()->attach($orderProducts);
			$order->update([
				'total' => $total,
				'iva' => $iva,
			]);
		});
	}

	private function calculateDiscountAmount(
		string $offerType,
		int $quantity,
		float $unitPrice,
		float $discountValue,
		int $buyQuantity
	): float {
		return match ($offerType) {
			'percentage' => $unitPrice * $quantity * (
				$discountValue > 1
				? $discountValue / 100
				: $discountValue
			),
			'x_for_y' => $buyQuantity > 0
				? $unitPrice * intdiv($quantity, $buyQuantity) * max(0, $buyQuantity - $discountValue)
				: 0,
			'fixed' => $quantity * $discountValue,
			default => 0,
		};
	}
}
