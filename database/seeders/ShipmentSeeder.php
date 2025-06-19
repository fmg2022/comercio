<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shipments')->insert([
            [
                'tracking_number' => 'COART-1242-AR',
                'shipped_at' => '2024-10-24',
                'delivered_at' => null,
                'shipping_provider_id' => 1,
            ],
            [
                'tracking_number' => 'OCA-5678-AR',
                'shipped_at' => '2024-10-25',
                'delivered_at' => '2024-10-30',
                'shipping_provider_id' => 6,
            ],
            [
                'tracking_number' => 'AND-9101-AR',
                'shipped_at' => '2024-10-26',
                'delivered_at' => '2024-10-27',
                'shipping_provider_id' => 9,
            ],
            [
                'tracking_number' => 'PYA-1121-AR',
                'shipped_at' => '2024-10-27',
                'delivered_at' => null,
                'shipping_provider_id' => 12,
            ],
        ]);
    }
}
