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
                'code' => 'PAYPAL',
                'name' => 'PayPal',
                'active' => true,
                'default_currency' => 'USD',
            ],
            [
                'code' => 'STRIPE',
                'name' => 'Stripe',
                'active' => true,
                'default_currency' => 'USD',
            ],
            [
                'code' => 'SQUARE',
                'name' => 'Square',
                'active' => false,
                'default_currency' => 'USD',
            ],
            [
                'code' => 'MERCADO_PAGO',
                'name' => 'Mercado Pago',
                'active' => true,
                'default_currency' => 'ARS',
            ],
        ]);
    }
}
