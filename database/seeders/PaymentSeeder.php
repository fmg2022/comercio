<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{

    public function run(): void
    {
        $orders = DB::table('orders')
            ->join('order_statuses', 'orders.order_status_id', '=', 'order_statuses.id')
            ->select('orders.id', 'orders.total', 'order_statuses.name')
            ->get();

        if ($orders->isEmpty()) {
            return;
        }

        $orders->each(function ($order) {
            $valor = $order->name == 'Cancelado' ? 'Cancelado' : (in_array($order->name, ['Completo', 'Entregado', 'Retirar']) ? 'Completado' : 'Pendiente');
            $paymentState_id = DB::table('payment_statuses')->whereLike('name', "%{$valor}%")->value('id');

            Payment::factory(1)->create([
                'amount' => $order->total,
                'order_id' => $order->id,
                'payment_status_id' => $paymentState_id,
            ]);
        });
    }
}
