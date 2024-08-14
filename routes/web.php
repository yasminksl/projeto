<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::middleware('auth')->group(function () {
    Route::inertia('/', 'Dashboard', [
        'title' => 'Dashboard'
    ]);
    Route::inertia('/orders', 'Orders/Index', [
        'title' => 'Pedidos'
    ]);
    Route::get('/clients', [ClientController::class, 'index']);
// });
