<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderPurchaseStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_purchase_states')->insert([
            ['slug' => 'pending', 'name' => 'Pendiente'],
            ['slug' => 'sent', 'name' => 'Enviado'],
            ['slug' => 'approved', 'name' => 'Aprobado'],
            ['slug' => 'rejected', 'name' => 'Rechazado'],
            ['slug' => 'refunded', 'name' => 'Reembolsado'],
            ['slug' => 'expired', 'name' => 'Expirado'],
            ['slug' => 'paid', 'name' => 'Pagado'],
            ['slug' => 'cancelled', 'name' => 'Cancelado'],
        ]);
    }
}
