<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = DB::table('orders')->pluck('id');

        foreach ($orders as $order) {
            DB::table('shipments')->insert([
                [
                    'order_id' => $order,
                    'shipment_state_id' => DB::table('shipment_states')->where('code', '!=', 'CANCELADO')->inRandomOrder()->first()->id
                ]
            ]);
        }
    }
}
