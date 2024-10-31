<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferConectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 4; $i++) {
            DB::table('offer_conections')->insert([
                'table' => 'products',
                'table_id' => $i,
            ]);
        }
        DB::table('offer_conections')->insert([
            'table' => 'products',
            'table_id' => 10,
        ]);

        DB::table('offer_conections')->insert([
            'table' => 'categories',
            'table_id' => 20,
        ]);
        DB::table('offer_conections')->insert([
            'table' => 'categories',
            'table_id' => 16,
        ]);
        DB::table('offer_conections')->insert([
            'table' => 'categories',
            'table_id' => 32,
        ]);
    }
}
