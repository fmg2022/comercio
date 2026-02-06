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
                'offer_type_id' => 1
            ],
            [
                'name' => '2 x 1',
                'description' => 'Lleva 2 productos por el precio de 1',
                'offer_type_id' => 2
            ],
            [
                'name' => '4 x 2',
                'description' => 'Lleva 4 productos por el precio de 2',
                'offer_type_id' => 2
            ],
            [
                'name' => 'Descuento 25%',
                'description' => '25% de descuento en productos seleccionados',
                'offer_type_id' => 1
            ],
        ]);
    }
}
