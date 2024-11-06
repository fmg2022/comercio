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
            'name' => 'Oferta Fideo y Harina selectas',
            'code' => 'FIDE',
            'type_discount' => 'Porcentaje',
            'discount' => 10,
        ]);

        DB::table('offers')->insert([
            'name' => 'Oferta Gaseosas',
            'code' => 'GASE',
            'type_discount' => 'Monto fijo',
            'discount' => 300,
        ]);

        DB::table('offers')->insert([
            'name' => 'Oferta Leches',
            'code' => 'LECHE',
            'type_discount' => 'Monto fijo',
            'discount' => 250,
        ]);

        DB::table('offers')->insert([
            'name' => 'Oferta Arroz',
            'code' => 'ARR',
            'type_discount' => 'Porcentaje',
            'discount' => 19,
        ]);
    }
}
