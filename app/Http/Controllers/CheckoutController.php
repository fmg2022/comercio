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

                $total += (($product->price * $product->pivot->quantity) - $product->pivot->discount);
            }
            $iva = $total * floatval(config('commerce.tax_rate', 21)) / 100;

            $order->update(['total' => $total, 'iva' => round($iva, 2)]);

            return $order;
        });

        CartFacade::clear();
        $cart->products()->detach();

        $accessToken = config('commerce.mercadopago.access_token');
        MercadoPagoConfig::setAccessToken($accessToken);

        $payment = Payment::create([
            'transaction_id' => 'pending_' . uniqid('', true),
            'paymentId' => 'pay_' . uniqid('', true),
            'provider_state' => 'pending',
            'checkout_url' => '#',
            'method' => $validated['payment_method'],
            'amount' => $order->total + $order->iva,
            'paid_at' => null,
            'order_id' => $order->id,
            'payment_state_id' => PaymentState::where('code', 'EN_PROCESO')->value('id'),
            'payment_provider_id' => PaymentProvider::where('code', 'MERCADO_PAGO')->value('id'),
        ]);

        $items = $order->products->map(function ($product) {
            $finalUnitPrice = $product->pivot->price - ($product->pivot->discount / $product->pivot->quantity);
            return [
                'id' => $product->id,
                'title' => $product->name . ' ' . strtoupper($product->brand->name) . ', ' . $product->weight,
                'quantity' => $product->pivot->quantity,
                'unit_price' => (float)$finalUnitPrice,
                'description' => $product->description,
                'currency_id' => config('commerce.mercadopago.currency_id'),
            ];
        });

        $baseUrl = route('webhook.mercadopago');

        $preferenceData = [
            'items' => $items,
            'payer' => [
                'name' => $order->user->name,
                'surname' => $order->user->surname,
                'email' => $order->user->email,
                'identification' => [
                    'type' => 'DNI',
                    'number' => $order->user->dni,
                ],
            ],
            'back_urls' => [
                'success' => route('payment.success'),
                'pending' => route('payment.pending'),
                'failure' => route('payment.failure'),
            ],
            'auto_return' => 'approved',
            'external_reference' => (string) $payment->id,
            'notification_url' => $baseUrl . '?source_news=webhooks',
        ];

        try {
            $client = new PreferenceClient();
            $preference = $client->create($preferenceData);

            $payment->update([
                'transaction_id' => $preference->id,
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
                'provider_state' => 'rejected',
                'payment_state_id' => DB::table('payment_states')->where('code', 'CANCELADO')->value('id'),
            ]);

            return back()->with('error', 'No se pudo iniciar el pago');
        }
    }

    public function handleWebhook(Request $request)
    {
        $type = $request->query('type');
        $paymentId = $request->query('data_id');

        if ($type !== 'payment') {
            Log::info('Webhook ignorado: tipo no es payment', ['type' => $type]);
            return response()->json(['status' => 'ignored'], 200);
        }

        if (!$paymentId) {
            Log::warning('Webhook: no se encontró data.id en la URL', ['full_url' => $request->fullUrl()]);
            return response()->json(['error' => 'Missing data.id parameter'], 400);
        }

        $accessToken = config('commerce.mercadopago.access_token');
        MercadoPagoConfig::setAccessToken($accessToken);

        try {
            $paymentClient = new PaymentClient();
            $mpPayment = $paymentClient->get('160220617939');

            if (!$mpPayment) {
                Log::warning("No se encontró información del pago para el ID: {$paymentId}");
                return response()->json(['error' => 'Payment not found'], 404);
            }

            $payment = Payment::where('id', $mpPayment->external_reference)->first();

            if (!$payment) {
                Log::warning("No se encontró orden local para external_reference: {$mpPayment->external_reference}");
                return response()->json(['error' => 'Order not found'], 404);
            }

            $payment->update([
                'paymentId'               => $mpPayment->collector_id,
                'provider_state'          => $mpPayment->status,
                'paid_at'                 => $mpPayment->status === 'approved' ? $mpPayment->date_approved : null,
                'nro_fee'                 => 1,
            ]);

            if ($mpPayment->status === 'approved') {
                $payment->order->update(['order_state_id' => OrderState::where('code', 'PAGADO')->value('id')]);
            }

            Log::info("Pago {$paymentId} procesado correctamente. Orden {$payment->order->id} actualizada.");

            return response()->json(['status' => 'ok'], 200);
        } catch (\MercadoPago\Exceptions\MPApiException $e) {
            $statusCode = $e->getApiResponse()->getStatusCode();
            $errorContent = $e->getApiResponse()->getContent();

            Log::error("Error de API de Mercado Pago al consultar el pago {$paymentId}.", [
                'status_code' => $statusCode,
                'error_details' => $errorContent,
            ]);
            return response()->json(['error' => 'Error fetching payment'], 500);
        }
    }
}
