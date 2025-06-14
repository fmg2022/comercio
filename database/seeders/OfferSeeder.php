<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offers')->insert([
            [
                'name' => 'Oferta Fideo y Harina selectas',
                'code' => 'FIDE',
                'type' => 'Porcentaje',
                'discount' => 10,
            ],
            [
                'name' => 'Oferta Gaseosas',
                'code' => 'GASE',
                'type' => '3x2',
                'discount' => 0,
            ],
            [
                'name' => 'Oferta Leches',
                'code' => 'LECHE',
                'type' => '2x1',
                'discount' => 0,
            ],
            [
                'name' => 'Oferta Arroz',
                'code' => 'ARR',
                'type' => 'Porcentaje',
                'discount' => 19,
            ],
        ]);
    }
}
