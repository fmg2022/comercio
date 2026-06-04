<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        foreach (array_keys(session()->all()) as $key) {
            if (str_starts_with($key, 'cart__cart_')) {
                session()->forget($key);
            }
        }

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
}
