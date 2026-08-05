<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_states')->insert([
            ['slug' => 'pending', 'name' => 'Pendiente'],
            ['slug' => 'paid', 'name' => 'Pagado'],
            ['slug' => 'completed', 'name' => 'Completado'],
            ['slug' => 'refunded', 'name' => 'Reembolsado'],
            ['slug' => 'cancelled', 'name' => 'Cancelado'],
        ]);
    }
}
