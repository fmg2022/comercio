<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['code' => 'PERCENTAGE', 'description' => 'Oferta por porcentaje'],
            ['code' => 'X_FOR_Y', 'description' => 'Oferta por cantidad'],
            // ['code' => 'FIXED', 'description' => 'Oferta fija'],
        ]);
    }
}
