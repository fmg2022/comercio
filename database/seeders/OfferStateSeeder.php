<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offer_states')->insert([
            ['code' => 'BORRADOR', 'description' => 'La oferta está en borrador y no es visible para los usuarios.'],
            ['code' => 'ACTIVA', 'description' => 'La oferta está activa y disponible para los usuarios.'],
            ['code' => 'PAUSADA', 'description' => 'La oferta está pausada temporalmente y no es visible para los usuarios.'],
            ['code' => 'EXPIRADA', 'description' => 'La oferta ha expirado y ya no es válida.'],
        ]);
    }
}
