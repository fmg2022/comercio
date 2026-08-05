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
            ['slug' => 'pending', 'name' => 'Pendiente'],
            ['slug' => 'active', 'name' => 'Activa'],
            ['slug' => 'paused', 'name' => 'Pausada'],
            ['slug' => 'expired', 'name' => 'Expirada'],
            ['slug' => 'cancelled', 'name' => 'Cancelada'],
        ]);
    }
}
