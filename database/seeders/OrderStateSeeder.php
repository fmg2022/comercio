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
            ['code' => 'CREADO', 'description' => 'El pedido se ha creado.'],
            ['code' => 'PENDIENTE', 'description' => 'Esperando confirmación de pago.'],
            ['code' => 'PAGADO', 'description' => 'Pago confirmado, preparando pedido.'],
            ['code' => 'COMPLETO', 'description' => 'El pedido se ha completado con éxito.'],
            ['code' => 'REEMBOLSADO', 'description' => 'El pedido ha sido reembolsado.'],
            ['code' => 'CANCELADO', 'description' => 'El pedido ha sido cancelado por el cliente/sistema.'],
        ]);
    }
}
