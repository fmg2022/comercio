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
		Order::factory(30)->create()->each(function ($order) {
			$products = DB::table('products')->inRandomOrder()
				->limit(rand(1, 8))
				->get()
				->mapWithKeys(function ($product) {
					return [
						$product->id => [
							'quantity' => rand(1, 6),
							'price' => $product->price,
							'discount' => 0
						]
					];
				})
				->toArray();

			$order->products()->attach($products);
		});
	}
}
