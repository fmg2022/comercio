<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_states')->insert([
            ['slug' => 'pending', 'name' => 'Pendiente'],
            ['slug' => 'approved', 'name' => 'Aprobado'],
            ['slug' => 'rejected', 'name' => 'Rechazado'],
            ['slug' => 'refunded', 'name' => 'Reembolsado'],
            ['slug' => 'expired', 'name' => 'Expirado'],
            ['slug' => 'cancelled', 'name' => 'Cancelado'],
        ]);
    }
}
