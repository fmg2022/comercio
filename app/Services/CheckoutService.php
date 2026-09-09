<?php

namespace App\Services;

use App\DTOs\CheckoutDTO;
use App\Exceptions\{CheckoutException, InsufficientStockException};
use App\Models\{Cart, Order, OrderState, Payment, PaymentProvider, PaymentState, Product, Shipping, ShippingState, User};
use Illuminate\Support\Facades\DB;

class CheckoutService
{

  public function checkout(
    User $user,
    CheckoutDTO $data
  ) {
    $cart = Cart::query()
      ->whereKey($data->cartId)
      ->where('user_id', $user->id)
      ->with('products')
      ->firstOrFail();

    if ($data->deliveryMethod === 'shipping') {
      $address = $user->addresses()->findOrFail($data->addressId);
      $shippingResult = app(ShippingCalculationService::class)->calculateForCheckout($address);
    } else {
      $shippingResult = [
        'error' => false,
        'is_feasible' => false,
        'shipping_rate_id' => null,
        'shipping_cost' => 0.0,
        'distance_km' => 0.0,
      ];
    }

    $result = DB::transaction(function () use ($cart, $user, $shippingResult, $data) {
      $isShipping = $data->deliveryMethod === 'shipping';
      $cartProducts = $cart->products;
      $productsIds = $cartProducts->pluck('id')->unique()->sort()->values();

      $lockedProducts = Product::whereIn('id', $productsIds)
        ->orderBy('id')
        ->lockForUpdate()
        ->get()
        ->keyBy('id');

      foreach ($cartProducts as $cartItem) {
        $product = $lockedProducts->get($cartItem->id);
        if (!$product) {
          throw new \Exception("Producto no encontrado: ID {$cartItem->id}");
        }

        $requestedQty = $cartItem->pivot->quantity;
        if ($product->stock < $requestedQty) {
          throw new InsufficientStockException(
            productId: $product->id,
            productName: $product->name,
            available: $product->stock,
            requested: $requestedQty,
          );
        }
      }

      $order = Order::create([
        'date' => now(),
        'total' => 0,
        'iva' => 0,
        'shipping_cost' => $shippingResult['shipping_cost'],
        'notes' => $data->notes,
        'order_state_id' => OrderState::where('slug', 'pending')->value('id'),
        'user_id' => $user->id,
      ]);

      $total = 0;

      foreach ($cartProducts as $productItem) {
        $offer = $productItem->getCurrentOffer();
        $templateOffer = $offer?->offerTemplate;

        $order->products()->attach($productItem->id, [
          'quantity' => $productItem->pivot->quantity,
          'price' => $productItem->price,
          'discount' => $productItem->pivot->discount,
          'offer_id' => $offer?->id ?? null,
          'offer_template_name' => $templateOffer?->name ?? null,
          'offer_type_slug' => $templateOffer?->offerType->slug ?? null,
        ]);

        $total += (($productItem->price * $productItem->pivot->quantity) - $productItem->pivot->discount);
      }

      $iva = round($total * floatval(config('commerce.tax_rate', 21)) / 100, 2);
      $total += $iva + $shippingResult['shipping_cost'];
      $order->update(['total' => $total, 'iva' => $iva]);

      foreach ($cartProducts as $cartItem) {
        $product = $lockedProducts->get($cartItem->id);
        $product->decrement('stock', $cartItem->pivot->quantity);
      }


      $trackingNumber = null;
      $deliveryDate = null;

      if ($isShipping) {
        $random = bin2hex(random_bytes(5));
        $date = date('YmdHis');
        $trackingNumber = "TRK-{$random}-{$date}";
        $deliveryDate = now()->addhours(random_int(1, 6));
      }

      $shippingStateSlug = $isShipping
        ? 'pending'
        : 'ready_for_pickup';

      Shipping::create([
        'order_id' => $order->id,
        'address_id' => $isShipping ? $data->addressId : null,
        'transport_user_id' => null,
        'shipping_states_id' => ShippingState::where('slug', $shippingStateSlug)->value('id'),
        'shipping_rate_id' => $shippingResult['shipping_rate_id'],
        'tracking_number' => $trackingNumber,
        'shipping_cost' => $shippingResult['shipping_cost'],
        'distance_km' => $shippingResult['distance_km'],
        'delivery_method' => $data->deliveryMethod,
        'estimated_delivery_date' => $deliveryDate,
        'delivered_at' => null,
        'notes' => null,
        'is_feasible' => $isShipping,
      ]);

      $payment = Payment::create([
        'transaction_id' => 'pending_' . uuid_create(),
        'paymentId' => 'pay_' . uuid_create(),
        'provider_state' => 'pending',
        'checkout_url' => '#',
        'method' => $data->paymentMethod,
        'amount' => $order->total,
        'paid_at' => null,
        'order_id' => $order->id,
        'payment_state_id' => PaymentState::where('slug', 'pending')->value('id'),
        'payment_provider_id' => PaymentProvider::where('code', strtolower($data->paymentMethod))->value('id'),
      ]);

      return [
        'order' => $order,
        'payment' => $payment,
      ];
    });
    return $result;
  }
}
