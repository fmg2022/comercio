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
		$validOffers = DB::table('offer_product')
			->join('offers', 'offer_product.offer_id', '=', 'offers.id')
			->join('offer_templates', 'offers.offer_template_id', '=', 'offer_templates.id')
			->join('offer_types', 'offer_templates.offer_type_id', '=', 'offer_types.id')
			->select(['offer_product.product_id', 'offer_product.offer_id', 'offer_types.slug', 'offer_templates.pay_qty', 'offer_templates.buy_qty', 'offer_templates.name', 'offers.start_date', 'offers.end_date'])
			->get()
			->groupBy('product_id');

		Order::factory(300)->create()->each(function ($order) use ($validOffers) {
			UserSessionHistory::factory()->create([
				'user_id' => $order->user_id,
				'login_at' => $order->date->subSeconds(rand(360, 900)),
				'logout_at' => $order->date->addSeconds(rand(180, 900)),
				'last_activity' => $order->date->subSeconds(rand(60, 180)),
				'is_active' => false,
			]);

			$currentOffers = $validOffers->filter(function ($offers) use ($order) {
				return $offers->contains(function ($offer) use ($order) {
					return $offer->start_date <= $order->date && $offer->end_date >= $order->date;
				});
			});

			$subtotal = 0;
			$discountTotal = 0;
			$products = [];

			$randomProducts = DB::table('products')->inRandomOrder()
				->select(['id', 'price'])
				->limit(rand(1, 15))
				->get();

			foreach ($randomProducts as $product) {
				$qty = rand(1, 6);
				$lineSubtotal = $qty * $product->price;
				$subtotal += $lineSubtotal;

				$offerData = $currentOffers->get($product->id)?->random();
				$discount = 0;
				$offerId = '';
				$typeslug = '';
				$offerName = '';

				if ($offerData) {
					$discount = round(
						$this->calculateDiscountAmount(
							$offerData->slug,
							$qty,
							$product->price,
							$offerData->pay_qty,
							$offerData->buy_qty ?? 0
						),
						2
					);
					$offerId = $offerData->offer_id;
					$offerName = $offerData->name;
					$typeslug = $offerData->slug;
					$discountTotal += $discount;
				}

				$products[$product->id] = [
					'quantity' => $qty,
					'price' => $product->price,
					'discount' => $discount,
					'offer_id' => $offerId,
					'offer_template_name' => $offerName,
					'offer_type_slug' => $typeslug,
				];
			}

			// Cálculo de total e IVA
			$base = $subtotal - $discountTotal;
			$taxRate = floatval(config('commerce.tax_rate', 21)) / 100;
			$iva = round($base * $taxRate, 2);
			$total = $base + $iva;

			// Adjuntar productos y actualizar la orden
			$order->products()->attach($products);
			$order->update([
				'total' => $total,
				'iva' => $iva,
			]);
		});
	}

	private function calculateDiscountAmount(string $offerType, int $quantity, float $unitPrice, float $discountValue, int $buyQuantity): float
	{
		if ($offerType === 'percentage') {
			$percentage = $discountValue;
			if ($percentage > 1) {
				$percentage /= 100;
			}
			return $unitPrice * $quantity * $percentage;
		}
		if ($offerType === 'x_for_y') {
			$sets = intdiv($quantity, $buyQuantity);
			$freeItemsPerSet = $buyQuantity - $discountValue;
			return $unitPrice * ($sets * $freeItemsPerSet);
		}
		if ($offerType === 'fixed') {
			return $discountValue * $quantity;
		}
		return 0;
	}
}
