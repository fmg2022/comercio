<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\{Cart, Order, OrderState, Payment, PaymentState};
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Http, Log, Mail};

class PayPalController extends Controller
{
    public function __construct(
        protected PayPalService $payPalService
    ) {}

    public function payCheckout(Order $order, Payment $payment)
    {
        $response = Http::get("https://dolarapi.com/v1/dolares/oficial")->json();
        $dolarValue = $response['compra'];
        $products = $order->products->map(function ($product) use ($dolarValue) {
            return [
                'name' => $product->name,
                'description' => $product->shortDescription,
                'quantity' => $product->pivot->quantity,
                'price' => round($product->pivot->price / $dolarValue, 2),
                'discount' => round($product->pivot->discount / $dolarValue, 2),
            ];
        })->toArray();

        $paypalOrder = $this->payPalService->createOrder($products, config('commerce.tax_rate', 21));

        if (!isset($paypalOrder['id'])) {
            return back()->with('error', 'No se pudo crear la orden en PayPal');
        }

        foreach ($paypalOrder['links'] as $link) {
            if ($link['rel'] === 'approve') {
                $payment->update([
                    'transaction_id' => $paypalOrder['id'],
                    'checkout_url' => $link['href'],
                ]);

                return redirect($link['href']);
            }
        }
        //buss !_J(,r>7  |  per uu6]W];^  sb-cbahe51841359@personal.example.com
        return back()->with('error', 'No se encontró URL de aprobación');
    }

    public function paySuccess(Request $request)
    {
        $paypalOrderId = $request->query('token');
        $capture = $this->payPalService->captureOrder($paypalOrderId);

        if (
            isset($capture['status']) &&
            $capture['status'] === 'COMPLETED'
        ) {
            $payment = Payment::where('transaction_id', $paypalOrderId)->first();

            if ($payment) {
                $payment->update([
                    'paid_at' => now(),
                    'paymentId' => $capture['id'],
                    'provider_state' => 'approved',
                    'payment_state_id' => PaymentState::where('code', 'APROBADO')->value('id'),
                ]);

                $payment->order->update(['order_state_id' => OrderState::where('code', 'PAGADO')->value('id')]);


                // Mail::to(auth()->user()->email)->send(new InvoiceMail($payment->order));
                Mail::to('maximo4735@gmail.com')->send(new InvoiceMail($payment->order));
            } else {
                Log::warning("No se encontró el pago para el ID: {$paypalOrderId}");
            }

            // Limpiar el carrito
            $cart = Cart::where('user_id', $payment->order->user->id)
                ->firstOrFail();
            $cart->products()->detach();

            return redirect()->route('home')->with('success', 'Pago completado con éxito');
        }

        return redirect()->route('paypal.cancel');
    }

    public function payCancel(Request $request)
    {
        $paypalOrderId = $request->query('token');
        $payment = Payment::where('transaction_id', $paypalOrderId)->first();

        if ($payment) {
            $payment->update([
                'provider_state' => 'rejected',
                'payment_state_id' => PaymentState::where('code', 'RECHAZADO')->value('id'),
            ]);
            $payment->order->update(['order_state_id' => OrderState::where('code', 'CANCELADO')->value('id')]);
        }

        return redirect()->route('home')->with('error', 'Pago cancelado');
    }
}
