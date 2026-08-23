<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offer_templates')->insert([
            [
                'name' => 'Descuento 10%',
                'description' => '10% de descuento en productos seleccionados',
                'offer_type_id' => 1,
                'buy_qty' => 1,
                'pay_qty' => 0.1,
            ],
            [
                'name' => '2 x 1',
                'description' => 'Lleva 2 productos por el precio de 1',
                'offer_type_id' => 2,
                'buy_qty' => 2,
                'pay_qty' => 1,
            ],
            [
                'name' => '3 x 2',
                'description' => 'Lleva 3 productos por el precio de 2',
                'offer_type_id' => 2,
                'buy_qty' => 3,
                'pay_qty' => 2,
            ],
            [
                'name' => 'Descuento 25%',
                'description' => '25% de descuento en productos seleccionados',
                'offer_type_id' => 1,
                'buy_qty' => 1,
                'pay_qty' => 0.25,
            ],
            [
                'name' => 'Descuento de $50',
                'description' => 'Descuento de $50 en productos seleccionados',
                'offer_type_id' => 3,
                'buy_qty' => 1,
                'pay_qty' => 50,
            ],
            [
                'name' => 'Descuento 15%',
                'description' => '15% de descuento en productos seleccionados',
                'offer_type_id' => 1,
                'buy_qty' => 1,
                'pay_qty' => 0.15,
            ],
            [
                'name' => 'Descuento de $500',
                'description' => 'Descuento de $500 en productos seleccionados',
                'offer_type_id' => 3,
                'buy_qty' => 1,
                'pay_qty' => 500,
            ],
            [
                'name' => 'Descuento de $1000',
                'description' => 'Descuento de $1000 en productos seleccionados',
                'offer_type_id' => 3,
                'buy_qty' => 1,
                'pay_qty' => 1000,
            ],
            [
                'name' => '3 x 1',
                'description' => 'Lleva 3 productos por el precio de 1',
                'offer_type_id' => 2,
                'buy_qty' => 3,
                'pay_qty' => 1,
            ],
            [
                'name' => 'Descuento 30%',
                'description' => '30% de descuento en productos seleccionados',
                'offer_type_id' => 1,
                'buy_qty' => 1,
                'pay_qty' => 0.3,
            ]
        ]);
    }
}
