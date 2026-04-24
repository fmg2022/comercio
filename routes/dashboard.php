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

  Route::prefix('dashboard')->group(function () {
    Route::group(['middleware' => ['permission:list my_section']], function () {
      Route::get('/addresses/my', [AddressController::class, 'myIndex'])->name('addresses.myIndex');
      Route::get('/orders/my', [OrderController::class, 'myIndex'])->name('orders.myIndex');
    });

    // User routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::resource('/users', UserController::class);
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    // Address routes
    Route::resource('/addresses', AddressController::class)->except(['show', 'create']);
    Route::post('/addresses/{id}/restore', [AddressController::class, 'restore'])->name('addresses.restore');

    // Product, category, brand routes
    Route::group(['middleware' => ['permission:manage products-and-attributes']], function () {
      Route::resource('/products', ProductController::class)->except(['index', 'show']);
      Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
      Route::get('/products/{id}/orders', [ProductController::class, 'ordersbyproduct'])->name('products.orders');

      // Category routes
      Route::resource('/categories', CategoryController::class)->except(['show', 'create', 'edit']);
      Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');

      // Brand routes
      Route::resource('/brands', BrandController::class)->except(['create', 'edit']);
      Route::post('/brands/{id}/restore', [BrandController::class, 'restore'])->name('brands.restore');
    });
    Route::resource('/products', ProductController::class)->only(['index', 'show'])->middleware('permission:list products');

    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index')->middleware('permission:list orders');
    Route::get('/orders/{id}/show', [OrderController::class, 'show'])->name('orders.show')->middleware('permission:show orders');
    Route::put('/orders/{order}/states', [OrderController::class, 'updateStates'])->name('orders.updateStates');

    // State & Payment routes
    Route::resource('/payments', PaymentController::class)->only(['index', 'update']);
    Route::put('/payments/{payment}/states', [PaymentController::class, 'updateStates'])->name('payments.updateStates');

    // Model states & types routes
    Route::prefix('states-types')->group(function () {
      Route::get('/', [DashboardController::class, 'indexStatesTypes'])->name('states-types.index')->middleware('permission:list state-type-tables');

      Route::group(['middleware' => ['permission:manage state-type-tables']], function () {
        Route::resource('/order-states', OrderStateController::class)->only(['store', 'update', 'destroy']);
        Route::resource('/offer-states', OfferStateController::class)->only(['store', 'update', 'destroy']);
        Route::resource('/offer-types', OfferTypeController::class)->only(['store', 'update', 'destroy']);
        Route::resource('/payment-states', PaymentStateController::class)->only(['store', 'update', 'destroy']);
        Route::resource('/shipment-states', ShipmentStateController::class)->only(['store', 'update', 'destroy']);
      });
    });
  });
});
