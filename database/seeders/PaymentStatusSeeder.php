<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_statuses')->insert([
            ['name' => 'Pendiente', 'description' => 'Esperando confirmación de pago.'],
            ['name' => 'Procesando', 'description' => 'El pago está siendo procesado.'],
            ['name' => 'Completado', 'description' => 'El pago se ha completado con éxito.'],
            ['name' => 'Fallido', 'description' => 'El pago ha fallado.'],
            ['name' => 'Reembolsado', 'description' => 'El pago ha sido reembolsado totalmente.'],
            ['name' => 'Parcialmente Reembolsado', 'description' => 'El pago ha sido reembolsado parcialmente.'],
            ['name' => 'Cancelado', 'description' => 'El pago ha sido cancelado por el cliente/sistema.'],
        ]);
    }
}
