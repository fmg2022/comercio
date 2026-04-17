<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OfferStateController;
use App\Http\Controllers\OfferTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStateController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentStateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipmentStateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified']], function () {
  Route::prefix('dashdoard')->group(function () {
    // User routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::resource('/users', UserController::class);
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    // Address routes
    Route::resource('/addresses', AddressController::class)->except(['show']);
    Route::post('/addresses/{id}/restore', [AddressController::class, 'restore'])->name('addresses.restore');

    // Product routes
    Route::resource('/products', ProductController::class);
    Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::get('/products/{id}/orders', [ProductController::class, 'ordersbyproduct'])->name('products.orders');

    // Order routes
    Route::resource('/orders', OrderController::class)->only(['index', 'show']);
    Route::put('/orders/{order}/states', [OrderController::class, 'updateStates'])->name('orders.updateStates');

    // Category, State & Payment routes
    Route::resource('/categories', CategoryController::class)->except(['show', 'create', 'edit']);
    Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::resource('/payments', PaymentController::class)->only(['index', 'update']);
    Route::put('/payments/{payment}/states', [PaymentController::class, 'updateStates'])->name('payments.updateStates');

    // Brand routes
    Route::resource('/brands', BrandController::class)->except(['create', 'edit']);
    Route::post('/brands/{id}/restore', [BrandController::class, 'restore'])->name('brands.restore');

    // Model states & types routes
    Route::prefix('states-types')->group(function () {
      Route::get('/', [DashboardController::class, 'indexStatesTypes'])->name('states-types.index');
      Route::resource('/order-states', OrderStateController::class)->only(['store', 'update', 'destroy']);
      Route::resource('/offer-states', OfferStateController::class)->only(['store', 'update', 'destroy']);
      Route::resource('/offer-types', OfferTypeController::class)->only(['store', 'update', 'destroy']);
      Route::resource('/payment-states', PaymentStateController::class)->only(['store', 'update', 'destroy']);
      Route::resource('/shipment-states', ShipmentStateController::class)->only(['store', 'update', 'destroy']);
    });
  });
});
