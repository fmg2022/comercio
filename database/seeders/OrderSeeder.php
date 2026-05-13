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
		Order::factory(80)->create()->each(function ($order) {
			$validOffers = DB::table('offer_product')
				->join('offers', 'offer_product.offer_id', '=', 'offers.id')
				->join('offer_templates', 'offers.offer_template_id', '=', 'offer_templates.id')
				->join('offer_types', 'offer_templates.offer_type_id', '=', 'offer_types.id')
				->whereDate('offers.end_date', '>=', $order->date)
				->whereDate('offers.start_date', '<=', $order->date)
				->get(['offer_product.product_id', 'offer_templates.id', 'offer_types.code', 'offer_templates.pay_qty', 'offer_templates.buy_qty'])
				->groupBy('product_id');

			$products = DB::table('products')->inRandomOrder()
				->limit(rand(1, 8))
				->get()
				->mapWithKeys(function ($product) use ($validOffers) {
					$offerData = $validOffers->get($product->id)?->random();
					$qty = rand(1, 6);

					return [
						$product->id => [
							'quantity' => $qty,
							'price' => $product->price,
							'discount' => $offerData ? round($this->applyDiscount($offerData->code, $qty, $product->price, $offerData->pay_qty, $offerData->buy_qty), 2) : 0,
							'offer_template_id' => $offerData->id ?? '',
							'offer_type_code' => $offerData->code ?? '',
						]
					];
				});

			$order->products()->attach($products->toArray());
		});
	}

	private function applyDiscount(string $offerType, int $quantity, float $price, float $pay_qty, int $buy_qty): float
	{
		if ($offerType === 'PERCENTAGE') {
			return $price * $pay_qty * $quantity;
		}
		if ($offerType === 'X_FOR_Y') {
			return $price * (intdiv($quantity, $buy_qty) * $pay_qty);
		}
		if ($offerType === 'FIXED') {
			return $pay_qty * $quantity;
		}
		return 0;
	}
}
