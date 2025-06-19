<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_statuses')->insert([
            ['name' => 'Pendiente', 'description' => 'Esperando confirmación de pago.'],
            ['name' => 'Procesando', 'description' => 'Pago confirmado, preparando pedido.'],
            ['name' => 'Completo', 'description' => 'El pedido se ha completado con éxito.'],
            ['name' => 'Retirar', 'description' => 'Listo para recoger en la tienda.'],
            ['name' => 'Delivery', 'description' => 'Enviado para entrega.'],
            ['name' => 'Entregado', 'description' => 'El pedido ha sido entregado al cliente.'],
            ['name' => 'Cancelado', 'description' => 'El pedido ha sido cancelado por el cliente/sistema.'],
        ]);
    }
}
