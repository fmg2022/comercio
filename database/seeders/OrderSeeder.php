<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
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
					$percentage = DB::table('offer_product')
						->join('offers', 'offer_product.offer_id', '=', 'offers.id')
						->join('offer_templates', 'offers.offer_template_id', '=', 'offer_templates.id')
						->join('offer_percentages', 'offer_templates.id', '=', 'offer_percentages.offer_template_id')
						->where('offer_product.product_id', $product->id)
						->inRandomOrder()
						->value('offer_percentages.percentage');
					$qty = rand(1, 6);
					return [
						$product->id => [
							'quantity' => $qty,
							'price' => $product->price,
							'discount' => $product->price * $percentage
						]
					];
				})
				->toArray();

			$order->products()->attach($products);
		});
	}
}
