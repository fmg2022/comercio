<?php

namespace Database\Seeders;

use App\Models\Payment;
use Carbon\Carbon;
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
            ->get(['orders.id', 'orders.total', 'orders.date', 'order_states.code']);
        $method = fake()->randomElement(['Tarjeta crédito', 'Tarjeta débito', 'Transferencia bancaria', 'Cuenta corriente']);

        foreach ($orders as $order) {
            $daysDelay = match ($method) {
                'Tarjeta crédito' => rand(0, 2),
                'Tarjeta débito' => rand(0, 1),
                'Transferencia bancaria' => rand(1, 5),
                'Cuenta corriente' => rand(0, 1),
                default => rand(0, 30),
            };
            $paidAt = null;
            if ($order->code !== 'CANCELADO') {
                $newPaitAt = Carbon::parse($order->date)->addDays($daysDelay);
                $paidAt = $newPaitAt->isFuture() ? now() : $newPaitAt;
            }

            Payment::factory()->create([
                'method' => $method,
                'nro_fee' =>  !in_array($method, ['Cuenta corriente', 'Transferencia bancaria']) ? rand(1, 12) : 1,
                'amount' => $order->total,
                'order_id' => $order->id,
                'paid_at' => $paidAt,
                'payment_state_id' => $order->code === 'CANCELADO'
                    ? DB::table('payment_states')->where('code', 'CANCELADO')->value('id')
                    : DB::table('payment_states')->where('code', '!=', 'CANCELADO')->inRandomOrder()->value('id'),
                'payment_provider_id' => DB::table('payment_providers')->where('active', true)->inRandomOrder()->value('id'),
            ]);
        }
    }
}
