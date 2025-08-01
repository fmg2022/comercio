<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            UserSeeder::class,
            OfferSeeder::class,
            OrderStatusSeeder::class,
            ShippingProviderSeeder::class,
            ShipmentSeeder::class,
            PaymentStatusSeeder::class,
            OrderSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
