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
            ['code' => 'Pendiente', 'description' => 'Pedido de compra pendiente de aprobación'],
            ['code' => 'Enviada al Proveedor', 'description' => 'Pedido de compra enviado al proveedor'],
            ['code' => 'Recibida', 'description' => 'Pedido de compra recibido por el proveedor'],
            ['code' => 'Pagada', 'description' => 'Pedido de compra pagada'],
            ['code' => 'Cancelada', 'description' => 'Pedido de compra cancelada'],
        ]);
    }
}
