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
        $orders = DB::table('orders')
            ->join('order_states', 'orders.order_state_id', '=', 'order_states.id')
            ->select('orders.id', 'orders.total', 'orders.date', 'order_states.code')
            ->get();

        $paymentStates = DB::table('payment_states')->pluck('id', 'code');
        $nonCanceledStateIds = $paymentStates->except('CANCELADO')->values()->all();

        $activeProviderIds = DB::table('payment_providers')
            ->where('active', true)
            ->pluck('id')
            ->all();

        if (empty($activeProviderIds)) {
            $this->command->warn('No active payment providers found. Skipping.');
            return;
        }

        foreach ($orders as $order) {
            $method = fake()->randomElement([
                'Tarjeta crédito',
                'Tarjeta débito',
                'Transferencia bancaria',
                'Mercado Pago'
            ]);
            $daysDelay = match ($method) {
                'Tarjeta crédito' => rand(0, 2),
                'Tarjeta débito' => rand(0, 1),
                'Transferencia bancaria' => rand(1, 5),
                'Mercado Pago' => rand(0, 1),
            };

            $paidAt = null;
            if ($order->code !== 'CANCELADO') {
                $calculatedPaidAt = Carbon::parse($order->date)->addDays($daysDelay);
                $paidAt = $calculatedPaidAt->isFuture() ? now() : $calculatedPaidAt;
            }

            $nroFee = in_array($method, ['Cuenta corriente', 'Transferencia bancaria'])
                ? 1
                : rand(1, 12);

            $paymentFactory = Payment::factory()
                ->state([
                    'method' => $method,
                    'nro_fee' => $nroFee,
                    'amount' => $order->total,
                    'paid_at' => $paidAt,
                    'payment_provider_id' => fake()->randomElement($activeProviderIds),
                    'order_id' => $order->id,
                ]);

            if ($order->code === 'CANCELADO') {
                $paymentFactory->forCanceledOrder();
            } else {
                $paymentFactory->state(['payment_state_id' => fake()->randomElement($nonCanceledStateIds)]);
            }

            $paymentFactory->create();
        }
    }
}
