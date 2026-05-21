<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard.index');

// Rutas para los productos
Route::get('/products/search', [IndexController::class, 'search'])->name('product.search');
Route::get('/products/{product}', [IndexController::class, 'showProduct'])->name('product.show');
Route::get('/products/category/{category}', [IndexController::class, 'getProductsCategory'])->name('product.findForCategory');
Route::get('/products/offers/{offer}', [IndexController::class, 'getProductsOffer'])->name('product.findForOffer');

Route::middleware(['auth', 'verified'])->group(function () {
    // Rutas para el carrito
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'addToCart'])->name('addToCart');
        Route::put('/update', [CartController::class, 'update'])->name('update');
        Route::delete('/{id}/products/{id_product}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/{id}/clear', [CartController::class, 'clearCart'])->name('clearCart');
    });

    // Ruta para la compra
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Rutas para las redirecciones de Mercado Pago (back_urls)
    Route::get('/payment/exito', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/fallo', [PaymentController::class, 'failure'])->name('payment.failure');
    Route::get('/payment/pendiente', [PaymentController::class, 'pending'])->name('payment.pending');

    // Ruta para notificaciones (webhook) - SIN protección CSRF
    Route::post('/webhook/mercadopago', [PaymentController::class, 'handleWebhook'])->name('webhook.mercadopago');

    // Rutas para el perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard
require __DIR__ . '/dashboard.php';

// Autenticación de usuarios
require __DIR__ . '/auth.php';
