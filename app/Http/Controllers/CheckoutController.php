<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderState;
use App\Models\Payment;
use App\Models\PaymentProvider;
use App\Models\PaymentState;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Joelwmale\Cart\Facades\CartFacade;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class CheckoutController extends Controller
{
    public function process(Request $request): RedirectResponse
    {
        if (!auth()->user()->getCurrentAddress()) {
            return back()->with('error', 'Debes crear una dirección antes de poder realizar el pago.');
        }

        $validated = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'notes' => 'nullable|string',
            'payment_method' => 'required|string|in:mercadopago,store,cash',
        ]);

        $cart = Cart::where('id', $validated['cart_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($cart->products->isEmpty()) {
            return back()->with('error', 'El carrito está vacío.');
        }

        CartFacade::setSessionKey('cart_' . auth()->user()->id);

        $order = DB::transaction(function () use ($cart, $validated) {
            $order = Order::create([
                'date' => now(),
                'total' => 0,
                'iva' => 0,
                'notes' => $validated['notes'],
                'order_state_id' => OrderState::where('code', 'CREADO')->value('id'),
                'user_id' => auth()->user()->id,
                'address_id' => auth()->user()->getCurrentAddress()->id,
            ]);
            $total = 0;

            foreach ($cart->products as $product) {
                $offerTemplate = $product->getCurrentOffer();
                $order->products()->attach($product->id, [
                    'quantity' => $product->pivot->quantity,
                    'price' => $product->price,
                    'discount' => $offerTemplate ? $product->getDiscountTotal($product->pivot->quantity, $offerTemplate->buy_qty, $offerTemplate->pay_qty, $offerTemplate->offerType->code) : 0,
                    'offer_template_id' => $offerTemplate ? $offerTemplate->id : '',
                    'offer_type_code' => $offerTemplate ? $offerTemplate->offerType->code : '',
                ]);

                $total += $product->pivot->getSubtotal;
            }
            $iva = $total * ((float) config('commerce.tax_rate') / 100);

            $order->update(['total' => $total, 'iva' => $iva]);
            return $order;
        });

        CartFacade::clear();
        $cart->products()->detach();

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        $payment = Payment::create([
            'provider_transaction_id' => '0',
            'provider_state' => 'pending',
            'checkout_url' => '#',
            'method' => $validated['payment_method'],
            'amount' => $order->total,
            'paid_at' => null,
            'order_id' => $order->id,
            'payment_state_id' => PaymentState::where('code', 'EN_PROCESO')->value('id'),
            'payment_provider_id' => PaymentProvider::where('code', 'MERCADO_PAGO')->value('id'),
        ]);

        $order->update(['payment_id' => $payment->id]);

        $items = $order->products->map(function ($product) {
            $finalUnitPrice = $product->pivot->price - ($product->pivot->discount / $product->pivot->quantity);
            return [
                'id' => $product->id,
                'title' => $product->name . ' ' . strtoupper($product->brand->name) . ', ' . $product->weight,
                'quantity' => $product->pivot->quantity,
                'unit_price' => (float) $finalUnitPrice,
                'description' => $product->description,
                'currency_id' => config('services.mercadopago.currency_id'),
            ];
        });

        $preferenceData = [
            'items' => $items,
            'payer' => [
                'name' => $order->user->name,
                'surname' => $order->user->surname,
                'email' => $order->user->email,
            ],
            'back_urls' => [
                'success' => route('payment.success'),
                'pending' => route('payment.pending'),
                'failure' => route('payment.failure'),
            ],
            'auto_return' => 'approved',
            'external_reference' => (string) $payment->id,
            'notification_url' => route('webhook.mercadopago', [], true),
        ];

        try {
            $client = new PreferenceClient();
            $preference = $client->create($preferenceData);

            $payment->update([
                'provider_transaction_id' => $preference->id,
                'checkout_url' => $preference->init_point,
            ]);

            return redirect($preference->init_point);
        } catch (MPApiException $e) {
            $responseBody = $e->getApiResponse()->getContent();
            Log::error('Error al crear preferencia MP', [
                'payment_id' => $payment->id,
                'status' => $e->getApiResponse()->getStatusCode(),
                'error' => $e->getMessage(),
                'response' => $responseBody,
            ]);
            $payment->update([
                'provider_state' => 'failed',
                'payment_state_id' => DB::table('payment_states')->where('code', 'CANCELADO')->value('id'),
            ]);

            return back()->with('error', 'No se pudo iniciar el pago');
        }
    }

    public function handleWebhook(Request $request)
    {
        if ($request->input('type') === 'payment' && $request->input('data.status') === 'approved') {
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

            try {
                $client = new PaymentClient();
                $paymentInfo = $client->get($request->input('data.id'));
                $payment = Payment::where('provider_transaction_id', $paymentInfo->external_reference)
                    ->orWhere('id', $paymentInfo->external_reference)
                    ->first();

                if (!$payment) {
                    Log::warning('Webhook: pago no encontrado', ['external_reference' => $paymentInfo->external_reference]);
                    return response()->json(['error' => 'Payment not found'], 404);
                }

                DB::transaction(function () use ($payment, $paymentInfo) {
                    $payment->update([
                        'provider_transaction_id' => $paymentInfo->id,
                        'provider_state' => $paymentInfo->status,
                        'paid_at' => $paymentInfo->status === 'approved' ? now() : null,
                        'nro_fee' => $paymentInfo->installments ?? $payment->nro_fee,
                    ]);
                    if ($paymentInfo->status === 'approved') {
                        $payment->order->update(['order_state_id' => OrderState::where('code', 'PAGADO')->value('id')]);
                    }
                });

                // $payment->order->user->email
                Mail::to('maximo4735@gmail.com')->send(new InvoiceMail($payment->order));
            } catch (\Exception $e) {
                Log::error('Error al procesar webhook de MercadoPago: ', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
