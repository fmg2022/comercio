<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'total' => 10000.00,
                'date' => now(),
                'user_id' => 1,
                'order_status_id' => 1,
                'offer_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'total' => 20500.00,
                'date' => now(),
                'user_id' => 2,
                'order_status_id' => 6,
                'offer_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
