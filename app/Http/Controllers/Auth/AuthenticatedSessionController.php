<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
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
        if (User::where('email', $request->email)->value('active') === 0) {
            throw ValidationException::withMessages([
                'email' => trans('Usuario no activo'),
            ]);
        }

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
    protected function loadCartFromDatabase(User $user): void
    {
        $cart = $user->cart;
        $cartItems = $cart->products()->get();

        if ($cartItems->count() === 0) {
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
            if (!$product || $product->stock <= 0) {
                $cart->products()->detach($product->id);

                return;
            }

            $quantity = min($product->stock, $product->pivot->quantity);
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
