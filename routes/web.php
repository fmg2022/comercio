<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('home');

// Ruta para notificaciones (webhook) - SIN protección CSRF
Route::post('/webhook/mercadopago', [MercadoPagoController::class, 'handleWebhook'])
    ->middleware('verify.mp.webhook')
    ->name('webhook.mercadopago');

// Rutas para los productos
Route::get('/products/search', [IndexController::class, 'search'])->name('product.search');
Route::get('/products/{product}', [IndexController::class, 'showProduct'])->name('product.show');
Route::get('/products/category/{category}', [IndexController::class, 'getProductsCategory'])->name('product.findForCategory');
Route::get('/products/offers/{offer}', [IndexController::class, 'getProductsOffer'])->name('product.findForOffer');

Route::middleware(['auth', 'verified'])->group(function () {
    // Rutas del dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [\App\Http\Controllers\DashboardController::class, 'redirectToDashboard'])
            ->name('dashboard.index');
        Route::get('/clients', [\App\Http\Controllers\DashboardController::class, 'index'])
            ->name('client.dashboard');
        Route::get('/admins', [\App\Http\Controllers\AdminDashboardController::class, 'index'])
            ->middleware('permission:list roles')
            ->name('admin.dashboard');
    });

    // Rutas para el carrito
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', function () {
            return view('pages.home.cart.index');
        })->name('index');
        Route::post('/add', [CartController::class, 'addToCart'])->name('addToCart');
        Route::put('/update', [CartController::class, 'update'])->name('update');
        Route::delete('/{cart}/clear', [CartController::class, 'clearCart'])->name('clearCart');
        Route::delete('/{id}/products/{id_product}', [CartController::class, 'remove'])->name('remove');
    });

    // Ruta para la compra
    Route::post('/store', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/checkout/{order}/{payment}', [MercadoPagoController::class, 'process'])->name('checkout.process');

    // Rutas para las redirecciones de Mercado Pago (back_urls)
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/exito', [MercadoPagoController::class, 'success'])->name('success');
        Route::get('/fallo', [MercadoPagoController::class, 'failure'])->name('failure');
        Route::get('/pendiente', [MercadoPagoController::class, 'pending'])->name('pending');
    });

    // Rutas para el pago con PayPal
    Route::prefix('paypal')->name('paypal.')->group(function () {
        Route::get('/checkout/{order}/{payment}', [PayPalController::class, 'payCheckout'])->name('checkout');
        Route::get('/success', [PayPalController::class, 'paySuccess'])->name('success');
        Route::get('/cancel', [PayPalController::class, 'payCancel'])->name('cancel');
    });

    // Rutas para el perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard
require __DIR__ . '/dashboard.php';

// Autenticación de usuarios
require __DIR__ . '/auth.php';
