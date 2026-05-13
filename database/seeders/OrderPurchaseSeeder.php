<?php

namespace Database\Seeders;

use App\Models\OrderPurchase;
use Illuminate\Database\Seeder;

class OrderPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderPurchase::factory(60)->create()->each(function ($purchase) {
            $products = $purchase->provider->products;
            $pivotData = [];
            $total = 0;
            foreach ($products as $product) {
                $priceP = $product->price * (1 - fake()->randomFloat(2, 0.1, 0.3));
                $qty = rand(1, 6);
                $total += ($priceP * $qty);
                $pivotData[$product->id] = [
                    'quantity' => $qty,
                    'purchase_price' => $priceP,
                    'suggested_sale_price' => $product->price,
                ];
            }
            $purchase->products()->attach($pivotData);
            $purchase->amount = $total;
            $purchase->save();
        });
    }
}
