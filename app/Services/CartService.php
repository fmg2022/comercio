<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Joelwmale\Cart\Facades\CartFacade;

class CartService
{
  /**
   * Cargar carrito desde base de datos
   */
  public function loadCartFromDatabase(User $user): void
  {
    $cart = $user->cart;

    if (!$cart) {
      return;
    }

    $cartItems = $cart->products()->get();

    if ($cartItems->isEmpty()) {
      return;
    }

    foreach ($cartItems as $cartItem) {
      $this->addCartItemToSession($cartItem, $cart);
    }
  }

  /**
   * Agregar item desde BD al carrito de sesión
   */
  protected function addCartItemToSession(Product $product, Cart $cart): void
  {
    try {
      if (!$product->inStock()) {
        $cart->products()->detach($product->id);
        return;
      }

      $quantity = min($product->stock, $product->pivot->quantity);
      $offerTemplate = $product->getCurrentOffer();
      $discount = $offerTemplate ?
        $product->getDiscountTotal(
          $quantity,
          $offerTemplate->buy_qty,
          $offerTemplate->pay_qty,
          $offerTemplate->offerType->code
        )
        : 0;

      CartFacade::add([
        'id' => $product->id,
        'name' => $product->name,
        'price' => $product->price,
        'quantity' => $quantity,
        'attributes' => [
          'brand' => $product->brand->name,
          'image' => $product->image,
          'description' => $product->description,
          'category' => $product->category->name,
          'discount' => $discount,
        ]
      ]);

      // Si la cantidad fue ajustada, actualizar en BD
      if ($quantity != $product->pivot->quantity) {
        $cart->products()->updateExistingPivot($product->id, ['quantity' => $quantity]);
      }
    } catch (\Exception $e) {
      Log::error('Error al agregar item al carrito de sesión', [
        'product_id' => $product->id,
        'error' => $e->getMessage()
      ]);
    }
  }
}
