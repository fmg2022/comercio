<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified']], function () {
  Route::prefix('pdf')->name('pdf.')->group(function () {
    Route::get('/orders/{order}', [App\Http\Controllers\PDFController::class, 'generatePDFOrders'])->name('order');
  });

  Route::prefix('dashboard')->group(function () {
    // Settings routes
    Route::middleware(['permission:manage settings'])->prefix('admin')->group(function () {
      Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings');
      Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');
    });

    // My routes
    Route::group(['middleware' => ['permission:list my_section']], function () {
      Route::get('my/addresses', [AddressController::class, 'myIndex'])->name('my.addresses.index');
      Route::get('my/orders', [OrderController::class, 'myIndex'])->name('my.orders.index');
      Route::get('my/orders/{id}/show', [OrderController::class, 'show'])->name('my.orders.show');
      Route::get('my/payments', [PaymentController::class, 'myIndex'])->name('my.payments.index');
      Route::get('my/cart', function () {
        $cart = auth()->user()->cart;
        abort_unless($cart, 404, 'Carrito no encontrado');

        $controller = resolve(CartController::class);
        return $controller->show($cart);
      })->name('my.cart.index');
    });

    // User routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::resource('/users', UserController::class);
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::put('/users/{user}/roles', [UserController::class, 'updateRole'])->name('users.updateRole');

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
    Route::prefix('orders')->name('orders.')->group(function () {
      Route::get('/', [OrderController::class, 'index'])->name('index')->middleware('permission:list orders');
      Route::get('/{id}/show', [OrderController::class, 'show'])->name('show')->middleware('permission:show orders');
      Route::put('/{order}/states', [OrderController::class, 'updateStates'])->name('updateStates');
      Route::get('/export', [OrderController::class, 'export'])->name('export');
      Route::post('/count', [OrderController::class, 'count'])->name('count');
    });

    // State & Payment routes
    Route::prefix('payments')->name('payments.')->group(function () {
      Route::resource('/', PaymentController::class)->only(['index', 'update']);
      Route::put('/{payment}/states', [PaymentController::class, 'updateStates'])->name('updateStates');
      Route::get('/export', [PaymentController::class, 'export'])->name('export');
      Route::post('/count', [PaymentController::class, 'count'])->name('count');
    });

    // Model states & types routes
    Route::prefix('states-types')->group(function () {
      Route::get('/', [App\Http\Controllers\DashboardController::class, 'indexStatesTypes'])->name('states-types.index')->middleware('permission:list state-type-tables');

      Route::group(['middleware' => ['permission:manage state-type-tables']], function () {
        Route::resource('/order-states', App\Http\Controllers\OrderStateController::class)->only(['store', 'update', 'destroy']);
        Route::resource('/offer-states', App\Http\Controllers\OfferStateController::class)->only(['store', 'update', 'destroy']);
        Route::resource('/offer-types', App\Http\Controllers\OfferTypeController::class)->only(['store', 'update', 'destroy']);
        Route::resource('/payment-states', App\Http\Controllers\PaymentStateController::class)->only(['store', 'update', 'destroy']);
      });
    });

    // Role routes
    Route::middleware(['permission:manage roles'])->group(function () {
      Route::resource('/roles', App\Http\Controllers\RoleController::class)->except(['show', 'create', 'edit']);
    });

    // Provider routes
    Route::middleware(['permission:manage providers'])->group(function () {
      Route::resource('/providers', ProviderController::class)->except(['show', 'create', 'edit']);
      Route::post('/providers/{id}/restore', [ProviderController::class, 'restore'])->name('providers.restore');
    });

    // Cart routes
    Route::group(['middleware' => ['permission:list carts']], function () {
      Route::prefix('carts')->name('carts.')->group(function () {
        Route::get('/', [CartController::class, 'dashboardIndex'])->name('index');
        Route::get('/{cart}', [CartController::class, 'show'])->name('show');
        Route::delete('/{id}/clear', [CartController::class, 'clearCart'])->name('clearCart');
        Route::delete('/{id}/products/{id_product}', [CartController::class, 'remove'])->name('remove');
      });
    });
  });
});
