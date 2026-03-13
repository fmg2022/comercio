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
            BrandSeeder::class,
            ProductSeeder::class,
            UserSeeder::class,
            AddressSeeder::class,
            FavouriteSeeder::class,
            CartSeeder::class,
            ProviderSeeder::class,
            OfferTypeSeeder::class,
            OfferStateSeeder::class,
            OfferTemplatesSeeder::class,
            OfferSeeder::class,
            ShipmentStateSeeder::class,
            ShipmentSeeder::class,
            OrderStateSeeder::class,
            OrderSeeder::class,
            PaymentProviderSeeder::class,
            PaymentStateSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
