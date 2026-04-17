<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OfferStateController;
use App\Http\Controllers\OfferTypeController;
use App\Http\Controllers\OrderStateController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentStateController;
use App\Http\Controllers\ShipmentStateController;
use Illuminate\Support\Facades\Route;

Route::get('/products/{id}', [ProductController::class, 'fetch']);
Route::get('/users/{id}', [UserController::class, 'fetch']);
Route::get('/addresses/{id}', [AddressController::class, 'fetch']);
Route::get('/categories/{id}', [CategoryController::class, 'fetch']);
Route::get('/categories', [CategoryController::class, 'getCategories']);
Route::get('/dashboard/sellers/cant', [DashboardController::class, 'cantSellers']);
Route::get('/dashboard/orders/cant', [DashboardController::class, 'cantOrders']);
Route::get('/payments/{id}', [PaymentController::class, 'fetch']);
Route::get('/order-states/{id}', [OrderStateController::class, 'fetch']);
Route::get('/offer-states/{id}', [OfferStateController::class, 'fetch']);
Route::get('/offer-types/{id}', [OfferTypeController::class, 'fetch']);
Route::get('/payment-states/{id}', [PaymentStateController::class, 'fetch']);
Route::get('/shipment-states/{id}', [ShipmentStateController::class, 'fetch']);
