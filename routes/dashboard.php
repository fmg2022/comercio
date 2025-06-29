<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified']], function () {
  Route::prefix('dashdoard')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::resource('/products', ProductController::class);
    Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
    Route::post('/orders/{id}/restore', [OrderController::class, 'restore'])->name('orders.restore');
  });

  // Route::resource('categories', CategoryController::class)->except(['show']);
  // Route::resource('users', ProfileController::class);
  // Route::resource('states', StateController::class)->except(['show']);
  // Route::resource('payments', PaymentController::class);
  // Route::resource('addresses', AddressController::class);
});
