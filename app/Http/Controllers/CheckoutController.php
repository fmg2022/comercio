<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderState;
use App\Models\Payment;
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
    public function process(Order $order, Payment $payment): RedirectResponse
    {
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

        $accessToken = config('commerce.mercadopago.access_token');
        MercadoPagoConfig::setAccessToken($accessToken);

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
                'success' => route('checkout.success'),
                'pending' => route('checkout.pending'),
                'failure' => route('checkout.failure'),
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

    // Rutas para redirecciones de Mercado Pago
    public function success(Request $request): RedirectResponse
    {
        $paymentId = $request->query('payment_id') ?? $request->query('collection_id');
        $payment_id = $request->query('external_reference') ?? $request->query('preference_id');

        if ($paymentId && $payment_id) {
            $payment = Payment::where('paymentId', $paymentId)
                ->orWhere('id', $payment_id)
                ->orWhere('transaction_id', $payment_id)
                ->first();

            if ($payment && $payment->provider_state !== 'approved') {
                $payment->update([
                    'paymentId' => $paymentId,
                    'provider_state' => 'approved',
                    'paid_at' => now(),
                ]);

                if ($payment->order) {
                    $payment->order->update(['order_state_id' => OrderState::where('code', 'PAGADO')->value('id')]);
                }
            }

            // Mail::to(auth()->user()->email)->send(new InvoiceMail($payment->order));
            Mail::to('maximo4735@gmail.com')->send(new InvoiceMail($payment->order));

            // Limpiar el carrito
            CartFacade::clear();
            $cart = Cart::where('user_id', auth()->id())
                ->firstOrFail();
            $cart->products()->detach();

            return redirect('/')->with('success', 'La compra se realizó con éxito.');
        }
        return redirect('/')->with('error', 'No se encontró el pago.');
    }

    public function failure(Request $request): RedirectResponse
    {
        $paymentId = $request->query('payment_id') ?? $request->query('collection_id');
        $payment_id = $request->query('external_reference') ?? $request->query('preference_id');

        if ($paymentId) {
            $pago = Payment::where('paymentId', $paymentId)
                ->orWhere('id', $payment_id)
                ->orWhere('transaction_id', $payment_id)
                ->first();
            if ($pago && $pago->provider_state !== 'rejected') {
                $pago->update([
                    'paymentId' => $paymentId,
                    'provider_state' => 'rejected'
                ]);
            }
        }

        return redirect('/')->with('error', 'El pago fue rechazado.');
    }

    public function pending(Request $request): RedirectResponse
    {
        $paymentId = $request->query('payment_id') ?? $request->query('collection_id');
        $payment_id = $request->query('external_reference') ?? $request->query('preference_id');

        if ($paymentId) {
            $pago = Payment::where('paymentId', $paymentId)
                ->orWhere('id', $payment_id)
                ->orWhere('transaction_id', $payment_id)
                ->first();
            if ($pago && $pago->provider_state !== 'pending') {
                $pago->update([
                    'paymentId' => $paymentId,
                    'provider_state' => 'pending'
                ]);
            }
        }

        return redirect('/')->with('warning', 'El pago está pendiente de acreditación.');
    }
}
