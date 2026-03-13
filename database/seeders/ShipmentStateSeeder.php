<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShipmentStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shipment_states')->insert([
            ['code' => 'PENDIENTE', 'description' => 'El envío está pendiente de ser procesado.'],
            ['code' => 'PROCESANDO', 'description' => 'El envío está siendo procesado.'],
            ['code' => 'EN_REPARTO', 'description' => 'El envío está en reparto.'],
            ['code' => 'ENTREGADO', 'description' => 'El envío ha sido entregado al destinatario.'],
            ['code' => 'INTENTO_FALLIDO', 'description' => 'Se ha intentado entregar el envío, pero ha fallado.'],
            ['code' => 'DEVUELTO', 'description' => 'El envío ha sido devuelto al remitente.'],
        ]);
    }
}
