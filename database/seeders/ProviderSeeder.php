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
        Provider::factory(11)->create()->each(function (Provider $provider) {
            $products = DB::table('products')->inRandomOrder()->take(rand(4, 10))->get(['id']);
            $pivotData = [];
            foreach ($products as $product) {
                $pivotData[$product->id] = [
                    'min_quantity' => rand(1, 15),
                    'is_preferred' => rand(0, 1) > 0,
                ];
            }
            $provider->products()->attach($pivotData);
        });
    }
}
