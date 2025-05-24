<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSedeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status')->insert([
            'name' => 'Pendiente',
        ]);
        DB::table('status')->insert([
            'name' => 'Procesando',
        ]);
        DB::table('status')->insert([
            'name' => 'Enviado',
        ]);
        DB::table('status')->insert([
            'name' => 'Entregado',
        ]);
        DB::table('status')->insert([
            'name' => 'Cancelado',
        ]);
    }
}
