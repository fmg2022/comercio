<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/products/{id}', [ProductController::class, 'fetch']);
Route::get('/users/{id}', [UserController::class, 'fetch']);
Route::get('/addresses/{id}', [AddressController::class, 'fetch']);
Route::get('/categories/{id}', [CategoryController::class, 'fetch']);
Route::get('/categories', [CategoryController::class, 'getCategories']);
Route::get('/dashboard/sellers/cant', [DashboardController::class, 'cantSellers']);
Route::get('/dashboard/orders/cant', [DashboardController::class, 'cantOrders']);
Route::get('/payments/{id}', [PaymentController::class, 'fetch']);
