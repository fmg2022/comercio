<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payments')->insert([
            ['name' => 'tarjeta credito'],
            ['name' => 'tarjeta debito'],
            ['name' => 'billetera virtual']
        ]);
    }
}
