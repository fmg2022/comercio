<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Joelwmale\Cart\Facades\CartFacade;

class CartController extends Controller
{
	protected CartService $cartService;

	public function __construct(CartService $cartService)
	{
		$this->cartService = $cartService;
	}

	public function index(): View
	{
		$user = auth()->user();
		if (CartFacade::getContent()->isEmpty()) {
			$this->cartService->loadCartFromDatabase($user);
		}

		return view('pages.home.cart.index', [
			'cartItems' => CartFacade::getContent(),
			'cart_id' => $user->cart->id,
		]);
	}

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
			$offerTemplate = $product->getCurrentOffer();
			$qty = $existItem ? $item->pivot->quantity + $validated['quantity'] : $validated['quantity'];
			$discount = $offerTemplate ?
				$product->getDiscountTotal(
					$qty,
					$offerTemplate->buy_qty,
					$offerTemplate->pay_qty,
					$offerTemplate->offerType->code
				)
				: 0;

			if ($existItem) {
				$cart->updateProduct($validated['id'], $item->pivot->quantity + $validated['quantity']);

				CartFacade::update(
					$validated['id'],
					[
						'quantity' => $validated['quantity'],
						'attributes' => [
							'discount' => $discount,
						]
					]
				);
			} else {
				$cart->attachProduct($validated['id'], $validated['quantity']);

				CartFacade::add([
					'id' => $validated['id'],
					'name' => $product->name,
					'price' => $product->price,
					'quantity' => $validated['quantity'],
					'attributes' => [
						'brand' => $product->brand->name,
						'image' => $product->image,
						'description' => $product->description,
						'category' => $product->category,
						'discount' => $discount,
					]
				]);
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

		DB::beginTransaction();
		try {
			CartFacade::update(
				$validated['id'],
				[
					'quantity' => [
						'relative' => false,
						'value' => $validated['quantity']
					]
				]
			);

			auth()->user()->cart->updateProduct($validated['id'], $validated['quantity']);
			DB::commit();
		} catch (\Throwable $th) {
			DB::rollBack();

			return redirect()->back()->with('error', 'Error al actualizar el carrito')->with('errorTh', $th);
		}

		return redirect()->back()->with('success', 'Producto actualizado en el carrito');
	}

	public function remove(string $id, string $id_product): RedirectResponse
	{
		CartFacade::remove($id_product);
		Cart::findOrFail($id)->detachProduct($id_product);

		return redirect()->back()->with('success', 'Producto eliminado del carrito');
	}

	public function clearCart(): RedirectResponse
	{
		CartFacade::clear();
		auth()->user()->cart->detachProduct([]);

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
