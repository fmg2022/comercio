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
            $offer->products()->attach(
                Product::pluck('id')->random(rand(1, 3))
            );
        });
    }
}
