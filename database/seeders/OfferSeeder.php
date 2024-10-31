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
            'initial_date' => '2024-10-29',
            'expiration_date' => '2024-11-01',
            'offer_conection_id' => 1,
        ]);
        DB::table('offers')->insert([
            'name' => 'Oferta Fideo y Harina selectas',
            'code' => 'FIDE',
            'type_discount' => 'Porcentaje',
            'discount' => 10,
            'initial_date' => '2024-10-25',
            'expiration_date' => '2024-11-01',
            'offer_conection_id' => 4,
        ]);

        DB::table('offers')->insert([
            'name' => 'Oferta Gaseosas',
            'code' => 'GASE',
            'type_discount' => 'Monto fijo',
            'discount' => 300,
            'initial_date' => '2024-11-01',
            'expiration_date' => '2024-11-15',
            'offer_conection_id' => 6,
        ]);

        DB::table('offers')->insert([
            'name' => 'Oferta Leches',
            'code' => 'LECHE',
            'type_discount' => 'Monto fijo',
            'discount' => 250,
            'initial_date' => '2024-11-05',
            'expiration_date' => '2024-11-21',
            'offer_conection_id' => 7,
        ]);
    }
}
