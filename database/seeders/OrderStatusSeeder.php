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
            ['name' => 'Pendiente', 'description' => 'Esperando confirmación de pago.', 'created_at' => now()],
            ['name' => 'Procesando', 'description' => 'Pago confirmado, preparando pedido.', 'created_at' => now()],
            ['name' => 'Completo', 'description' => 'El pedido se ha completado con éxito.', 'created_at' => now()],
            ['name' => 'Retirar', 'description' => 'Listo para recoger en la tienda.', 'created_at' => now()],
            ['name' => 'Delivery', 'description' => 'Enviado para entrega.', 'created_at' => now()],
            ['name' => 'Entregado', 'description' => 'El pedido ha sido entregado al cliente.', 'created_at' => now()],
            ['name' => 'Cancelado', 'description' => 'El pedido ha sido cancelado por el cliente/sistema.', 'created_at' => now()],
        ]);
    }
}
