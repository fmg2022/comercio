<?php

use Illuminate\Support\Facades\Route;

Route::get('/products/{id}', [App\Http\Controllers\ProductController::class, 'fetch']);
Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'fetch']);
Route::get('/users/{id}/roles', [App\Http\Controllers\UserController::class, 'fetchRoles']);
Route::get('/addresses/{id}', [App\Http\Controllers\AddressController::class, 'fetch']);
Route::get('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'fetch']);
Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'getCategories']);
Route::get('/dashboard/sellers/cant', [App\Http\Controllers\DashboardController::class, 'cantSellers']);
Route::get('/dashboard/orders/cant', [App\Http\Controllers\DashboardController::class, 'cantOrders']);
Route::get('/payments/{id}', [App\Http\Controllers\PaymentController::class, 'fetch']);
Route::get('/order-states/{id}', [App\Http\Controllers\OrderStateController::class, 'fetch']);
Route::get('/offer-states/{id}', [App\Http\Controllers\OfferStateController::class, 'fetch']);
Route::get('/offer-types/{id}', [App\Http\Controllers\OfferTypeController::class, 'fetch']);
Route::get('/payment-states/{id}', [App\Http\Controllers\PaymentStateController::class, 'fetch']);
Route::get('/brands/{id}', [App\Http\Controllers\BrandController::class, 'fetch']);
Route::get('/roles/{id}', [App\Http\Controllers\RoleController::class, 'fetch']);
Route::get('/providers/{id}', [App\Http\Controllers\ProviderController::class, 'fetch']);
Route::get('/carts/{id_cart}/product/{id_product}', [App\Http\Controllers\CartController::class, 'fetch']);
