<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Offer::factory(16)->create()->each(function ($offer) {
            $products = Product::query()
                ->when(
                    $offer->offerTemplate->offerType === 'fixed',
                    fn($query) => $query->where(
                        'price',
                        '>',
                        $offer->offerTemplate->pay_qty
                    )
                )
                ->pluck('id');

            if ($products->isEmpty()) {
                return;
            }

            $quantity = rand(
                1,
                min(4, $products->count())
            );

            $offer->products()->attach(
                $products->random($quantity)
            );
        });
    }
}
