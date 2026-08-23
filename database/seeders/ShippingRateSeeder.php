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
                'max_distance' => 1,
                'cost' => 0,
            ],
            [
                'min_distance' => 1,
                'max_distance' => 3, // <= 2 km
                'cost' => 1000,
            ],
            [
                'min_distance' => 3, // > 2 km
                'max_distance' => 5,
                'cost' => 1500,
            ],
            [
                'min_distance' => 5,
                'max_distance' => 8,
                'cost' => 2000,
            ],
            [
                'min_distance' => 8,
                'max_distance' => 11,
                'cost' => 3000,
            ],
        ];

        foreach ($shippingRates as $shippingRate) {
            \App\Models\ShippingRate::create($shippingRate);
        }
    }
}
