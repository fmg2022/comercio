<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('providers')->insert([
            ['name' => 'Correo Argentino', 'email' => 'coargentino@mail.com.ar', 'phone' => '+541123654789', 'website' => 'correoargentino.com.ar'],
            ['name' => 'OCA', 'email' => 'oca@mail.com.ar', 'phone' => '+541124256789', 'website' => 'oca.com.ar'],
            ['name' => 'Andreani', 'email' => '', 'phone' => '+541123456789', 'website' => 'andreani.com.ar'],
            ['name' => 'PedidosYa', 'email' => '', 'phone' => '+541184256789', 'website' => 'pedidosya.com.ar'],
        ]);

        DB::table('shipping_providers')->insert([
            ['provider_id' => '1', 'service_level' => 'estandar'],
            ['provider_id' => '1', 'service_level' => 'refrigerado'],
            ['provider_id' => '1', 'service_level' => 'voluminoso'],
            ['provider_id' => '1', 'service_level' => 'programado'],
            ['provider_id' => '2', 'service_level' => 'estandar'],
            ['provider_id' => '2', 'service_level' => 'refrigerado'],
            ['provider_id' => '2', 'service_level' => 'express'],
            ['provider_id' => '3', 'service_level' => 'estandar'],
            ['provider_id' => '3', 'service_level' => 'express'],
            ['provider_id' => '3', 'service_level' => 'congelado'],
            ['provider_id' => '3', 'service_level' => 'voluminoso'],
            ['provider_id' => '4', 'service_level' => 'estandar'],
            ['provider_id' => '4', 'service_level' => 'express'],
            ['provider_id' => '4', 'service_level' => 'programado'],
        ]);
    }
}
