<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\OrderUpdateController;
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
        ->except('edit');
    Route::post('/users/{user}/profile-photo', UserImageController::class);

    // Orders
    Route::resource('orders', OrderController::class)
        ->except(['edit', 'update']);

    // Orders Update
    Route::post('/orders/update-dates', [OrderUpdateController::class, 'updateDates']);
    Route::post('/orders/update-payment', [OrderUpdateController::class, 'updatePayment']);
    Route::post('/orders/update-order-values', [OrderUpdateController::class, 'updateOrderValues']);
    Route::post('/orders/update-order-client', [OrderUpdateController::class, 'updateOrderClient']);

    // Orders Update Products
    Route::post('/orders/update-products', [OrderProductController::class, 'updateProducts']);
    Route::patch('/orders/{order}/products/{product}', [OrderProductController::class, 'updateProductQuantity']);
    Route::delete('/orders/{order}/products/{product}', [OrderProductController::class, 'removeProduct']);

    // Clients
    Route::resource('clients', ClientController::class);

    // Products
    Route::resource('products', ProductController::class);
});
