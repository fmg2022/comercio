<?php

namespace Database\Seeders;

use App\Models\Order;
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
			->select(['offer_product.product_id', 'offer_templates.id as template_id', 'offer_types.code', 'offer_templates.pay_qty', 'offer_templates.buy_qty', 'offers.start_date', 'offers.end_date'])
			->get()
			->groupBy('product_id');

		Order::factory(80)->create()->each(function ($order) use ($validOffers) {
			$currentOffers = $validOffers->filter(function ($offers) use ($order) {
				return $offers->contains(function ($offer) use ($order) {
					return $offer->start_date <= $order->date &&
						$offer->end_date >= $order->date;
				});
			});
			$products = DB::table('products')->inRandomOrder()
				->limit(rand(1, 8))
				->get()
				->mapWithKeys(function ($product) use ($currentOffers) {
					$offerData = $currentOffers->get($product->id)?->random();
					$qty = rand(1, 6);

					$discount = 0;
					$templateId = '';
					$typeCode = '';

					if ($offerData) {
						$discount = round($this->calculateDiscountAmount($offerData->code, $qty, $product->price, $offerData->pay_qty, $offerData->buy_qty ?? 0), 2);
						$templateId = $offerData->template_id;
						$typeCode = $offerData->code;
					}

					return [
						$product->id => [
							'quantity' => $qty,
							'price' => $product->price,
							'discount' => $discount,
							'offer_template_id' => $templateId,
							'offer_type_code' => $typeCode,
						]
					];
				});

			$order->products()->attach($products->toArray());
		});
	}

	private function calculateDiscountAmount(string $offerType, int $quantity, float $unitPrice, float $discountValue, int $buyQuantity): float
	{
		if ($offerType === 'PERCENTAGE') {
			return round($unitPrice * $quantity * (1 - $discountValue), 2);
		}
		if ($offerType === 'X_FOR_Y') {
			return round($unitPrice * ((intdiv($quantity, $buyQuantity) * ($buyQuantity - $discountValue))), 2);
		}
		if ($offerType === 'FIXED') {
			return round($discountValue * $quantity, 2);
		}
		return 0;
	}
}
