<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Joelwmale\Cart\Facades\CartFacade;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $this->loadCartFromDatabase($request->user());

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        CartFacade::clear();

        return redirect('/');
    }

    /**
     * Cargar carrito desde base de datos
     */
    protected function loadCartFromDatabase($user): void
    {
        Log::info('Cargando carrito desde BD para usuario', ['user_id' => $user->id]);

        $cart = $user->cart;
        $cartItems = $cart->products()->get();

        Log::info('Items encontrados en BD', ['count' => $cartItems->count()]);

        if ($cartItems->count() === 0) {
            Log::info('No hay items en el carrito, saliendo');
            return;
        }

        foreach ($cartItems as $cartItem) {
            $this->addCartItemToSession($cartItem, $cart);
        }
    }

    /**
     * Agregar item desde BD al carrito de sesión
     */
    protected function addCartItemToSession($product, $cart): void
    {
        try {
            if (!$product || $product->stock <= 0) {
                Log::warning('Producto no disponible, eliminando pivot de carrito', [
                    'cart_id' => $cart->id,
                    'product_id' => $product->id
                ]);

                $cart->products()->detach($product->id);

                return;
            }

            // CartFacade::setSessionKey('user_' . auth()->id());
            // Ajustar cantidad si excede el stock disponible
            $quantity = min($product->stock, $product->pivot->quantity);
            CartFacade::add([
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'attributes' => [
                    'mark' => $product->mark,
                    'image' => $product->image,
                    'description' => $product->description,
                    'category' => $product->category,
                ]
            ]);

            Log::debug('Item agregado al carrito de sesión', [
                'product_id' => $product->pivot->product_id,
                'quantity' => $quantity,
            ]);

            // Si la cantidad fue ajustada, actualizar en BD
            if ($quantity != $product->pivot->quantity) {
                $cart->products()->updateExistingPivot($product->id, ['quantity' => $quantity]);

                Log::debug('Cantidad ajustada en BD', [
                    'cart_item_id' => $product->pivot->id,
                    'old_quantity' => $product->pivot->quantity,
                    'new_quantity' => $quantity
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al agregar item al carrito de sesión', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
