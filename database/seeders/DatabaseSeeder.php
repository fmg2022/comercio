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
            SettingsSeeder::class,
            RolesAndPermissionsSeeder::class,
            CreateSuperAdminUserSeeder::class,
            UserSeeder::class,
            AddressSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            FavouriteSeeder::class,
            CartSeeder::class,
            ProviderSeeder::class,
            OfferTypeSeeder::class,
            OfferStateSeeder::class,
            OfferTemplatesSeeder::class,
            OfferSeeder::class,
            OrderStateSeeder::class,
            OrderSeeder::class,
            ShippingRateSeeder::class,
            ShippingStateSeeder::class,
            ShippingSeeder::class,
            PaymentProviderSeeder::class,
            PaymentStateSeeder::class,
            PaymentSeeder::class,
            OrderPurchaseStateSeeder::class,
            OrderPurchaseSeeder::class,
        ]);
    }
}
