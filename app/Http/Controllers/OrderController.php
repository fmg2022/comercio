<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderState;
use App\Models\Payment;
use App\Models\Shipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Joelwmale\Cart\Facades\CartFacade;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('pages.dashboard.order.index', [
            'orders' => Order::orderByDesc('date')->paginate(10),
            'ordersDeleted' => Order::onlyTrashed()->paginate(10, pageName: 'pageDeleted'),
            'orderStates' => OrderState::all('code', 'id'),
        ]);
    }

    public function show(String $id): View
    {
        $order = Order::withTrashed()->findOrFail($id);
        return view('pages.dashboard.order.show', compact('order'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);

        $cart = Cart::find($validated['cart_id']);
        $products = $cart->products()->get();
        CartFacade::setSessionKey('cart_' . auth()->user()->id);

        $order = DB::transaction(function () use ($products) {
            $order = Order::create([
                'date' => now(),
                'total' => 0,
                'order_state_id' => OrderState::where('code', 'CREADO')->value('id'),
                'user_id' => auth()->user()->id,
                'address_id' => auth()->user()->getCurrentAddress()->id,
            ]);

            foreach ($products as $product) {
                $offerTemplate = $product->getCurrentOffer();

                $order->products()->attach($product->id, [
                    'quantity' => $product->pivot->quantity,
                    'price' => $product->price,
                    'discount' => $offerTemplate ? $product->getDiscountTotal($product->pivot->quantity, $offerTemplate->id) : 0,
                    'offer_template_id' => $offerTemplate ? $offerTemplate->id : '',
                    'offer_type_code' => $offerTemplate ? $offerTemplate->offerType->code : '',
                ]);
            }
            Log::info('Order created successfully', ['order_id' => $order->id, 'order_total' => $order->total]);
            return $order;
        });

        CartFacade::clear();
        $cart->products()->detach();

        // Tiene que ir a Payment Model (store) y una ruta que lo llame (create) desde un controller
        // Solo para comprobar la creación de la orden, págos y envíos (TABLAS)
        $payment = Payment::factory()->create([
            'checkout_url' => null,
            'method' => 'bank_transfer',
            'amount' => $order->total,
            'paid_at' => null,
            'order_id' => $order->id,
            'payment_state_id' => DB::table('payment_states')->where('code', 'PENDIENTE')->value('id'),
            'payment_provider_id' => DB::table('payment_providers')->where('code', 'MERCADO_PAGO')->value('id'),
        ]);
        // Tiene que ser redirecionado a una página de pagos (Paypal, MercadoPago,...), de ahí que accedan a la ruta payments y cree uno nuevo.

        $payment->update([
            'payment_state_id' => DB::table('payment_states')->where('code', 'EN_PROCESO')->value('id'),
        ]);

        // Cuando el pago ha sido confirmado (APROBADO) se procede a crear el envío
        $payment->update([
            'payment_state_id' => DB::table('payment_states')->where('code', 'APROBADO')->value('id'),
            'checkout_url' => 'https://www.mercadopago.com.ar/checkout/payments/result/?payment_id=123456789',
            'paid_at' => now(),
        ]);

        Shipment::factory()->create([
            'order_id' => $order->id,
            'shipment_state_id' => DB::table('shipment_states')->where('code', 'PENDIENTE')->value('id'),
        ]);
        return redirect()->route('home')->with('success', 'La orden ha sido creada exitosamente');
    }

    public function destroy(String $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $order->delete();
        $order->deleted_at = now();
        $order->save();
        return redirect()->route('orders.index');
    }

    public function restore(String $id): RedirectResponse
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();
        return redirect()->route('orders.index');
    }

    public function updateStates(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'states' => 'required|exists:order_states,id',
        ]);

        $order->update([
            'order_state_id' => $validated['states']
        ]);

        return redirect()->route('orders.index');
    }
}
