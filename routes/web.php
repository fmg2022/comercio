<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('home');

// Rutas para los productos
Route::get('/products/{id}', [ProductController::class, 'showOne'])->name('product.show');
Route::get('/products', [ProductController::class, 'getAllProducts'])->name('product.listAll');

// Ruta para las categorías
Route::get('/fetch-categories', [CategoryController::class, 'getCategories']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard
require __DIR__ . '/dashboard.php';

// Autenticación de usuarios
require __DIR__ . '/auth.php';

// Route::group(['middleware' => ['auth', 'verified']], function () {
    // Route::prefix('comercio')->name('comercio.')->group(function () {
        // Route::get('/', [IndexController::class, 'index'])->name('home');
        // Route::post('/add-to-cart', [IndexController::class, 'addToCart'])->name('add_to_cart');
        // Route::post('/increment', [IndexController::class, 'increment'])->name('increment'); // hacer un fetch
        // Route::post('/decrement', [IndexController::class, 'decrement'])->name('decrement'); // hacer un fetch
        // Route::post('/remove', [IndexController::class, 'remove'])->name('remove');
    // });

    // View de carrito
    // Route::prefix('cart')->name('cart.')->group(function () {
    // Route::get('/', [CartController::class, 'index'])->name('index');
    // Route::post('/increment', [CartController::class, 'increment'])->name('increment'); // hacer un fetch
    // Route::post('/decrement', [CartController::class, 'decrement'])->name('decrement'); // hacer un fetch
    // Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    // Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    // });
// });
