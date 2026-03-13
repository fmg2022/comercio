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
			$products = DB::table('products')->inRandomOrder()
				->limit(rand(1, 8))
				->get()
				->mapWithKeys(function ($product) {
					$offerData = DB::table('offer_product')
						->join('offers', 'offer_product.offer_id', '=', 'offers.id')
						->join('offer_templates', 'offers.offer_template_id', '=', 'offer_templates.id')
						->join('offer_types', 'offer_templates.offer_type_id', '=', 'offer_types.id')
						->where('offer_product.product_id', $product->id)
						->select('offer_templates.id', 'offer_types.code', 'offer_templates.pay_qty', 'offer_templates.buy_qty')
						->inRandomOrder()
						->first();
					$qty = rand(1, 6);
					$isEmptyOffer = empty($offerData);

					return [
						$product->id => [
							'quantity' => $qty,
							'price' => $product->price,
							'discount' => !$isEmptyOffer ? round($this->appyDiscount($offerData->code, $qty, $product->price, $offerData->pay_qty, $offerData->buy_qty), 2) : 0,
							'offer_template_id' => !$isEmptyOffer ? $offerData->id : '',
							'offer_type_code' => !$isEmptyOffer ? $offerData->code : '',
						]
					];
				})
				->toArray();

			$order->products()->attach($products);
		});
	}

	private function appyDiscount(string $offerType, int $quantity, float $price, float $pay_qty, int $buy_qty): float
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
