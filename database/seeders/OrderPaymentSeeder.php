<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\OrderPayment;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderPaymentSeeder extends Seeder
{

	public function run(): void
	{
		$orders = DB::table('orders')
			->join('order_statuses', 'orders.order_status_id', '=', 'order_statuses.id')
			->select('orders.id', 'orders.total', 'orders.date', 'order_statuses.name')
			->get();

		if ($orders->isEmpty()) {
			return;
		}

		$orders->each(function ($order) {
			$valor = $order->name == 'Cancelado' ? 'Cancelado' : (in_array($order->name, ['Completo', 'Entregado', 'Retirar']) ? 'Completado' : 'Pendiente');

			if ($valor != 'Cancelado') {

				$methodPayment = Payment::inRandomOrder()->first();
				$paymentState_id = DB::table('payment_statuses')->whereLike('name', "%{$valor}%")->value('id');

				$fees = $methodPayment->name == 'tarjeta credito' ? $fees = rand(1, 12) : 1;
				OrderPayment::factory($fees)->create([
					'order_id' => $order->id,
					'payment_id' => $methodPayment->id,
					'payment_status_id' => $paymentState_id,
				])->each(function ($paymentOrder, $index) use ($order, $fees) {
					$paymentOrder->nr_fee = $fees > 1 ? $index + 1 : 1;
					$paymentOrder->date = $fees > 1 ? Carbon::parse($order->date)->addMonths($index + 1) : $order->date;
					$paymentOrder->amount = $order->total / $fees;
					$paymentOrder->save();
				});
			}
		});
	}
}
