<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shippingRates = [
            [
                'min_distance' => 0,
                'max_distance' => 0.5,
                'cost' => 0,
            ],
            [
                'min_distance' => 0.5,
                'max_distance' => 2, // <= 2 km
                'cost' => 1000,
            ],
            [
                'min_distance' => 2, // > 2 km
                'max_distance' => 4.1,
                'cost' => 1500,
            ],
            [
                'min_distance' => 4.1,
                'max_distance' => 6,
                'cost' => 2000,
            ],
            [
                'min_distance' => 6,
                'max_distance' => 7.75,
                'cost' => 3000,
            ],
            [
                'min_distance' => 7.75,
                'max_distance' => 10,
                'cost' => 3500,
            ],
        ];

        foreach ($shippingRates as $shippingRate) {
            \App\Models\ShippingRate::create($shippingRate);
        }
    }
}
