<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified']], function () {
  Route::prefix('dashdoard')->group(function () {
    // User routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::resource('/users', UserController::class);
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    // Product routes
    Route::resource('/products', ProductController::class);
    Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::get('/products/{id}/orders', [ProductController::class, 'ordersbyproduct'])->name('products.orders');

    // Order routes
    Route::resource('/orders', OrderController::class)->only(['index', 'show', 'destroy']);
    Route::put('/orders_line/{order}', [OrderController::class, 'editLine'])->name('orderLine.edit');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{id}/restore', [OrderController::class, 'restore'])->name('orders.restore');
  });

  // Route::resource('categories', CategoryController::class)->except(['show']);
  // Route::resource('users', ProfileController::class);
  // Route::resource('states', StateController::class)->except(['show']);
  // Route::resource('payments', PaymentController::class);
  // Route::resource('addresses', AddressController::class);
});
