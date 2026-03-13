<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Provider::factory(8)->create()->each(function ($provider) {
            $products = DB::table('products')->inRandomOrder()->take(rand(4, 10))->get(['id', 'price']);
            $provider->products()->attach($products->mapWithKeys(function ($product) {
                $randDays = rand(0, 5);
                return [
                    $product->id => [
                        'price' => $product->price / (1 + rand(10, 30) / 100),
                        'stock' => rand(20, 60),
                        'delivery_date' => $randDays > 0 ? now()->addDays($randDays) : null,
                    ]
                ];
            }));
        });
    }
}
