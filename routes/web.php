<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserImageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Login
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('logout', [LoginController::class, 'destroy'])->middleware('auth');

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index']);

    // Users
    Route::resource('users', UserController::class)
        ->middleware('auth');
    Route::post('/users/{user}/profile-photo', UserImageController::class);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/create', [OrderController::class, 'create']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);

    // Orders Update
    Route::post('/orders/update-dates', [OrderController::class, 'updateDates']);
    Route::post('/orders/update-payment', [OrderController::class, 'updatePayment']);
    Route::post('/orders/update-order-values', [OrderController::class, 'updateOrderValues']);
    Route::post('/orders/update-order-client', [OrderController::class, 'updateOrderClient']);

    // Orders Update Products

    Route::post('/orders/update-products', [OrderController::class, 'updateProducts']);
    Route::patch('/orders/{order}/products/{product}', [OrderController::class, 'updateProductQuantity']);
    Route::delete('/orders/{order}/products/{product}', [OrderController::class, 'removeProduct']);

    // Clients
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/clients/create', [ClientController::class, 'create']);
    Route::post('/clients', [ClientController::class, 'store']);
    Route::get('/clients/{client}', [ClientController::class, 'show']);
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit']);
    Route::patch('/clients/{client}', [ClientController::class, 'update']);
    Route::delete('/clients/{client}', [ClientController::class, 'destroy']);

    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/create', [ProductController::class, 'create']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/products/{product}/edit', [ProductController::class, 'edit']);
    Route::patch('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});
