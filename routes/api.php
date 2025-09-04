<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/products/{id}', [ProductController::class, 'fetch']);
Route::get('/users/{id}', [UserController::class, 'fetch']);
Route::get('/addresses/{id}', [AddressController::class, 'fetch']);
