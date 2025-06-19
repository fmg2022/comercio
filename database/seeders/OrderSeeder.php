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
        Order::factory(10)->create()->each(function ($order) {
            $products = DB::table('products')->inRandomOrder()
                ->limit(rand(1, 8))
                ->get()
                ->mapWithKeys(function ($product) {
                    return [
                        $product->id => [
                            'quantity' => rand(1, 6),
                            'price' => $product->price,
                            'discount' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    ];
                })
                ->toArray();

            $order->products()->attach($products);

            $order->update([
                'total' => $order->products()->sum(DB::raw('order_product.price * order_product.quantity'))
            ]);
        });
    }
}
