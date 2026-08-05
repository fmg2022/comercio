<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Models\{Cart, Order, Product};
use Illuminate\Contracts\View\View;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
	public function addToCart(CartRequest $request): RedirectResponse
	{
		$validated = $request->validated();
		DB::beginTransaction();
		try {
			$cart = auth()->user()->cart;

			$item = $cart->products()
				->where('product_id', $validated['id'])
				->lockForUpdate()
				->first();

			$existItem = $item && $item->exists();

			$product = $existItem ? $item : Product::findOrFail($validated['id']);
			$offerTemplate = \App\Models\Offer::find($product->activeOffer())?->offerTemplate;
			$qty = $existItem ? $item->pivot->quantity + $validated['quantity'] : $validated['quantity'];

			$discount = $offerTemplate ?
				$product->getDiscountTotal(
					$qty,
					$offerTemplate->buy_qty,
					$offerTemplate->pay_qty,
					$offerTemplate->offerType->slug
				)
				: 0;

			if ($existItem) {
				$cart->updateProduct($validated['id'], $qty, $discount);
			} else {
				$cart->attachProduct($validated['id'], $qty, $discount);
			}

			DB::commit();
		} catch (\Throwable $th) {
			DB::rollBack();

			return redirect()->back()->with('error', 'Error al agregar al carrito')->with('errorTh', $th);
		}

		return redirect()->back()->with('success', 'Producto agregado al carrito');
	}

	public function update(CartRequest $request): RedirectResponse
	{
		$validated = $request->validated();

		$product = Product::findOrFail($validated['id']);
		$offerTemplate = $product->getCurrentOffer();
		$discount = $offerTemplate ?
			$product->getDiscountTotal(
				$validated['quantity'],
				$offerTemplate->buy_qty,
				$offerTemplate->pay_qty,
				$offerTemplate->offerType->slug
			)
			: 0;

		auth()->user()->cart->updateProduct($validated['id'], $validated['quantity'], $discount);

		return redirect()->back()->with('success', 'Producto actualizado en el carrito');
	}

	public function remove(string $id, string $id_product): RedirectResponse
	{
		Cart::findOrFail($id)->detachProduct($id_product);

		return redirect()->back()->with('success', 'Producto eliminado del carrito');
	}

	public function clearCart(Cart $cart): RedirectResponse
	{
		$cart->detachProduct([]);

		return redirect()->back()->with('success', 'Carrito vaciado');
	}

	// Dashboard
	public function dashboardIndex(): View
	{
		$carts = Cart::select(['id', 'user_id', 'updated_at'])
			->with('user:id,name,surname')
			->withCount('products')->paginate(10);
		$carts->map(fn($cart) => $cart->fullName = $cart->user->fullName());

		return view('pages.dashboard.cart.index', [
			'carts' => $carts,
		]);
	}

	public function show(Cart $cart): View
	{
		return view('pages.dashboard.cart.show', [
			'cart' => $cart,
		]);
	}

	public function addFromOrder(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'order_id' => 'required|integer|exists:orders,id',
		]);

		$order = Order::findOrFail($validated['order_id']);
		$cart = $order->user->cart;

		foreach ($order->products as $product) {
			$orderQty = $product->pivot->quantity;
			$existProduct = $cart->products()->where('product_id', $product->id)->first();

			if ($existProduct) {
				$cart->products()->updateExistingPivot($product->id, ['quantity' => $existProduct->pivot->quantity + $orderQty]);
			} else {
				$cart->products()->attach($product->id, ['quantity' => $orderQty]);
			}
		}

		return redirect()->back()->with('success', 'Productos agregados al carrito');
	}

	public function fetch(string $id_cart, string $id_product): JsonResponse
	{
		$cart = Cart::findOrFail($id_cart);
		$product = Product::findOrFail($id_product);

		return response()->json([
			'id' => $id_product,
			'quantity' => $cart->products()->where('product_id', $product->id)->first()->pivot->quantity,
		]);
	}
}
