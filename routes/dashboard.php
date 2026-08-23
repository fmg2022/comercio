<?php

use App\Http\Controllers\{
  AddressController,
  BrandController,
  CartController,
  CategoryController,
  OrderController,
  PaymentController,
  ProductController,
  ProviderController,
  RoleController,
  UserController
};
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified']], function () {
  Route::prefix('pdf')->name('pdf.')->group(function () {
    Route::get('/orders/{order}', [App\Http\Controllers\PDFController::class, 'generatePDFOrders'])->name('order');
  });

  Route::prefix('dashboard')->group(function () {
    // Settings routes
    Route::middleware(['permission:manage settings'])->prefix('site')->name('site.')->group(function () {
      Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
      Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    });

    // My routes
    Route::get('my/orders', [OrderController::class, 'myIndex'])->name('my.orders.index')->middleware('permission:view_any_own_orders');
    Route::get('my/orders/{id}/show', [OrderController::class, 'show'])->name('my.orders.show')->middleware('permission:view_own_order');
    Route::get('my/payments', [PaymentController::class, 'myIndex'])->name('my.payments.index')->middleware('permission:view_any_own_payments');
    Route::get('my/carts', function () {
      $cart = auth()->user()->cart;
      abort_unless($cart, 404, 'Carrito no encontrado');

      $controller = resolve(CartController::class);
      return $controller->show($cart);
    })->name('my.cart.index')->middleware('permission:view_any_own_cart');

    // User routes
    Route::prefix('/addresses')->name('profile.')->group(function () {
      Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('index');
      // Address routes
      Route::prefix('/addresses')->name('addresses.')->group(function () {
        Route::post('/', [AddressController::class, 'store'])->name('store');
        Route::put('/{address}', [AddressController::class, 'update'])->name('update');
        Route::patch('/default', [AddressController::class, 'updateDefault'])->name('updateDefault');
      });
    });
    Route::prefix('users')->name('users.')->group(function () {
      Route::get('/', [UserController::class, 'index'])->name('index')->middleware('permission:view_any_users');
      Route::get('/{id}/show', [UserController::class, 'show'])->name('show')->middleware('permission:view_users');
      Route::post('/', [UserController::class, 'store'])->name('store')->middleware('permission:create_users');
      Route::put('/{user}', [UserController::class, 'update'])->name('update')->middleware('permission:update_users');
      Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy')->middleware('permission:delete_users');
      Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore')->middleware('permission:delete_users');
      Route::put('/{user}/roles', [UserController::class, 'updateRole'])->name('updateRole')->middleware('permission:update_roles');
    });

    // Address routes
    Route::prefix('addresses')->name('addresses.')->group(function () {
      // Route::get('/', [AddressController::class, 'index'])->name('index')->middleware('permission:view_any_addresses');
      Route::post('/', [AddressController::class, 'store'])->name('store')->middleware('permission:create_addresses');
      Route::put('/{address}', [AddressController::class, 'update'])->name('update')->middleware('permission:update_addresses');
      Route::patch('/default', [AddressController::class, 'updateDefault'])->name('updateDefault')->middleware('permission:update_addresses');
      // Route::delete('/{address}', [AddressController::class, 'destroy'])->name('destroy')->middleware('permission:delete_addresses');
    });

    // Role routes
    Route::prefix('roles')->name('roles.')->group(function () {
      Route::get('/', [RoleController::class, 'index'])->name('index')->middleware('permission:view_any_roles');
      Route::post('/', [RoleController::class, 'store'])->name('store')->middleware('permission:create_roles');
      Route::put('/{role}', [RoleController::class, 'update'])->name('update')->middleware('permission:update_roles');
      Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy')->middleware('permission:delete_roles');
    });

    // Product, category, brand routes
    Route::prefix('products')->name('products.')->group(function () {
      Route::get('/', [ProductController::class, 'index'])->name('index')->middleware('permission:view_any_products');
      Route::get('/{id}/show', [ProductController::class, 'show'])->name('show')->middleware('permission:view_product');
      Route::post('/', [ProductController::class, 'store'])->name('store')->middleware('permission:create_products');
      Route::put('/{product}', [ProductController::class, 'update'])->name('update')->middleware('permission:edit_products');
      Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy')->middleware('permission:delete_products');
      Route::post('/{id}/restore', [ProductController::class, 'restore'])->name('restore')->middleware('permission:delete_products');
      Route::get('/{id}/orders', [ProductController::class, 'ordersbyproduct'])->name('orders')->middleware('permission:view_orders');
    });
    Route::group(['middleware' => ['permission:view_any_product_attributes']], function () {
      // Category routes
      Route::resource('/categories', CategoryController::class)->except(['show', 'create', 'edit']);
      Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');

      // Brand routes
      Route::resource('/brands', BrandController::class)->except(['create', 'edit']);
      Route::post('/brands/{id}/restore', [BrandController::class, 'restore'])->name('brands.restore');
    });

    // Offer routes
    Route::livewire('/offers', 'pages::dashboard.offer.index')->name('offers.index')->middleware('permission:view_any_offers');

    // Order routes
    Route::prefix('orders')->name('orders.')->group(function () {
      Route::get('/', [OrderController::class, 'index'])->name('index')->middleware('permission:view_any_orders');
      Route::get('/{id}/show', [OrderController::class, 'show'])->name('show')->middleware('permission:view_orders');
      Route::put('/{order}/states', [OrderController::class, 'updateStates'])->name('updateStates');
      Route::get('/export', [OrderController::class, 'export'])->name('export');
      Route::post('/count', [OrderController::class, 'count'])->name('count');
      Route::get('/filter', [OrderController::class, 'filter'])->name('filter');
    });

    // Payment routes
    Route::prefix('payments')->name('payments.')->group(function () {
      Route::resource('/', PaymentController::class)->only(['index', 'update']);
      Route::put('/{payment}/states', [PaymentController::class, 'updateStates'])->name('updateStates');
      Route::get('/export', [PaymentController::class, 'export'])->name('export');
      Route::post('/count', [PaymentController::class, 'count'])->name('count');
    });

    // Model states & types routes
    Route::prefix('states-types')->group(function () {
      Route::get('/', [App\Http\Controllers\DashboardController::class, 'indexStatesTypes'])->name('states-types.index')->middleware('permission:view_any_state_types');

      Route::group(['middleware' => ['permission:manage_state_types']], function () {
        Route::resource('/order-states', App\Http\Controllers\OrderStateController::class)->only(['store', 'update', 'destroy']);
        Route::resource('/offer-states', App\Http\Controllers\OfferStateController::class)->only(['store', 'update', 'destroy']);
        Route::resource('/offer-types', App\Http\Controllers\OfferTypeController::class)->only(['store', 'update', 'destroy']);
        Route::resource('/payment-states', App\Http\Controllers\PaymentStateController::class)->only(['store', 'update', 'destroy']);
      });
    });

    // Provider routes
    Route::prefix('providers')->name('providers.')->group(function () {
      Route::get('/', [ProviderController::class, 'index'])->name('index')->middleware('permission:view_any_providers');
      Route::post('/', [ProviderController::class, 'store'])->name('store')->middleware('permission:create_providers');
      Route::put('/{provider}', [ProviderController::class, 'update'])->name('update')->middleware('permission:update_providers');
      Route::middleware(['permission:delete_providers'])->group(function () {
        Route::delete('/{provider}', [ProviderController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [ProviderController::class, 'restore'])->name('restore');
      });
    });

    // Cart routes
    Route::group(['middleware' => ['permission:view_any_carts']], function () {
      Route::prefix('carts')->name('carts.')->group(function () {
        Route::get('/', [CartController::class, 'dashboardIndex'])->name('index');
        Route::get('/{cart}', [CartController::class, 'show'])->name('show');
        Route::delete('/{cart}/clear', [CartController::class, 'clearCart'])->name('clearCart');
        Route::delete('/{id}/products/{id_product}', [CartController::class, 'remove'])->name('remove');
        Route::post('/to_order', [CartController::class, 'addFromOrder'])->name('addFromOrder');
      });
    });
  });
});
