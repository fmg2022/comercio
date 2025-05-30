<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSedeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_statuses')->insert([
            ['name' => 'Pendiente'],
            ['name' => 'Procesando'],
            ['name' => 'Enviado'],
            ['name' => 'Entregado'],
            ['name' => 'Cancelado'],
            ['name' => 'Completo'],
        ]);
    }
}
