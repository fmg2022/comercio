<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offer_types')->insert([
            ['slug' => 'percentage', 'name' => 'Oferta por porcentaje'],
            ['slug' => 'x_for_y', 'name' => 'Oferta por cantidad'],
            ['slug' => 'fixed', 'name' => 'Oferta por precio fijo'],
        ]);
    }
}
