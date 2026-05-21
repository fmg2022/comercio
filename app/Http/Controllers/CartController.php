<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Models\Cart as ModelsCart;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Joelwmale\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
	public function index(): View
	{
		return view('pages.home.cart.index', [
			'cartItems' => Cart::getContent(),
			'cart_id' => auth()->user()->cart->id,
			'tax' => Cart::getSubTotalWithoutConditions() * floatval(config('commerce.tax_rate')) / 100,
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

			if ($item && $item->exists()) {
				$cart->updateProduct($validated['id'], $item->pivot->quantity + $validated['quantity']);

				Cart::update(
					$validated['id'],
					[
						'quantity' => $validated['quantity'],
					]
				);
			} else {
				$cart->attachProduct($validated['id'], $validated['quantity']);

				$product = Product::findOrFail($validated['id']);
				Cart::add([
					'id' => $validated['id'],
					'name' => $product->name,
					'price' => $product->price,
					'quantity' => $validated['quantity'],
					'attributes' => [
						'brand' => $product->brand->name,
						'image' => $product->image,
						'description' => $product->description,
						'category' => $product->category,
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
			Cart::update(
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
		Cart::remove($id_product);
		ModelsCart::findOrFail($id)->detachProduct($id_product);

		return redirect()->back()->with('success', 'Producto eliminado del carrito');
	}

	public function clearCart(): RedirectResponse
	{
		Cart::clear();
		auth()->user()->cart->detachProduct([]);

		return redirect()->back()->with('success', 'Carrito vaciado');
	}

	// Dashboard
	public function dashboardIndex(): View
	{
		$carts = ModelsCart::select(['id', 'user_id', 'updated_at'])
			->with('user:id,name,surname')
			->withCount('products')->paginate(10);
		$carts->map(fn($cart) => $cart->fullName = $cart->user->fullName());

		return view('pages.dashboard.cart.index', [
			'carts' => $carts,
		]);
	}

	public function show(ModelsCart $cart): View
	{
		return view('pages.dashboard.cart.show', [
			'cart' => $cart,
		]);
	}

	public function fetch(string $id_cart, string $id_product): JsonResponse
	{
		$cart = ModelsCart::findOrFail($id_cart);
		$product = Product::findOrFail($id_product);

		return response()->json([
			'id' => $id_product,
			'quantity' => $cart->products()->where('product_id', $product->id)->first()->pivot->quantity,
		]);
	}
}
