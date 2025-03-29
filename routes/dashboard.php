<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'verified']], function () {
  Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
  Route::get('/products', [ProductController::class, 'index'])->name('products.index');

  // Route::resource('products', ProductController::class);
  // Route::resource('categories', CategoryController::class)->except(['show']);
  // Route::resource('users', ProfileController::class);
  // Route::resource('orders', OrderController::class);
  // Route::resource('states', StateController::class)->except(['show']);
  // Route::resource('payments', PaymentController::class);
  // Route::resource('addresses', AddressController::class);
});
