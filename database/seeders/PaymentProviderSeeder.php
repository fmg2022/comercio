<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_providers')->insert([
            [
                'code' => 'paypal',
                'name' => 'PayPal',
                'active' => true,
                'default_currency' => 'USD',
            ],
            [
                'code' => 'stripe',
                'name' => 'Stripe',
                'active' => true,
                'default_currency' => 'USD',
            ],
            [
                'code' => 'square',
                'name' => 'Square',
                'active' => false,
                'default_currency' => 'USD',
            ],
            [
                'code' => 'mercadopago',
                'name' => 'Mercado Pago',
                'active' => true,
                'default_currency' => 'ARS',
            ],
        ]);
    }
}
