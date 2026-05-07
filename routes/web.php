<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\OrderController;
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

    // Ruta para la orden
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // Rutas para el perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard
require __DIR__ . '/dashboard.php';

// Autenticación de usuarios
require __DIR__ . '/auth.php';
