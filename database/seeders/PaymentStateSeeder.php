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
            ['code' => 'PENDIENTE', 'description' => 'Pago creado, esperando confirmación.'],
            ['code' => 'EN_PROCESO', 'description' => 'El proveedor está validando (banco, antifraude).'],
            ['code' => 'APROBADO', 'description' => 'Pago aprobado por el proveedor.'],
            ['code' => 'RECHAZADO', 'description' => 'Pago rechazado.'],
            ['code' => 'REEMBOLSADO', 'description' => 'Pago reembolsado totalmente.'],
            ['code' => 'EXPIRADO', 'description' => 'Pago expirado.'],
            ['code' => 'EN_DEVOLUCION', 'description' => 'El cliente desconoció el pago.'],
            ['code' => 'CANCELADO', 'description' => 'Pago cancelado por el cliente/sistema.'],
        ]);
    }
}
