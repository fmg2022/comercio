<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = DB::table('orders')->join('order_states', 'orders.order_state_id', '=', 'order_states.id')
            ->get(['orders.id', 'orders.total', 'order_states.code']);

        foreach ($orders as $order) {
            Payment::factory()->create([
                'amount' => $order->total,
                'order_id' => $order->id,
                'payment_state_id' => $order->code === 'CANCELADO' ? DB::table('payment_states')->where('code', 'CANCELADO')->value('id') : DB::table('payment_states')->where('code', '!=', 'CANCELADO')->inRandomOrder()->value('id'),
                'payment_provider_id' => DB::table('payment_providers')->where('active', true)->inRandomOrder()->value('id'),
            ]);
        }
    }
}
