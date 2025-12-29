<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Joelwmale\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
	public function index(): View
	{
		return view('pages.cart.index', [
			'cart' => Cart::getContent(),
			'shipping' => 532,
			'tax' => 0.06 // rand(0, 15) / 100 Impuesto establecido por el comercio
		]);
	}

	public function addToCart(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'id' => 'required|exists:products,id',
			'quantity' => 'required|integer|min:1',
		]);

		$product = Product::find($validated['id']);

		Cart::add([
			'id' => $validated['id'],
			'name' => $product->name,
			'price' => $product->price,
			'quantity' => $validated['quantity'],
			'attributes' => [
				'mark' => $product->mark,
				'image' => $product->image,
				'description' => $product->description,
				'category' => $product->category,
			]
		]);

		$cart = auth()->user()->cart;
		$product = Product::find($validated['id']);
		$exitProduct = $cart->products()->where('product_id', $validated['id'])->exists();

		if ($exitProduct) {
			$cart->products()->updateExistingPivot(
				$validated['id'],
				['quantity' => DB::raw('quantity + ' . $validated['quantity'])]
			);
		} else {
			$cart->products()->attach(
				$validated['id'],
				[
					'price' => $product->price,
					'quantity' => $validated['quantity']
				]
			);
		}

		return redirect()->back()->with('success', 'Producto agregado al carrito');
	}

	public function update(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'id' => 'required|exists:products,id',
			'quantity' => 'required|integer|min:1',
		]);

		Cart::update(
			$validated['id'],
			[
				'quantity' => [
					'relative' => false,
					'value' => $validated['quantity']
				]
			]
		);

		auth()->user()->cart->products()
			->updateExistingPivot($validated['id'], ['quantity' => $validated['quantity']]);

		return redirect()->back()->with('success', 'Producto actualizado en el carrito');
	}

	public function remove(string $id): RedirectResponse
	{
		Cart::remove($id);
		auth()->user()->cart->products()->detach($id);

		return redirect()->back()->with('success', 'Producto eliminado del carrito');
	}
}
