<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::middleware('auth')->group(function () {
    Route::inertia('/', 'Dashboard');

    Route::inertia('/orders', 'Orders/Index');

    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/clients/create', [ClientController::class, 'create']);
    Route::post('/clients', [ClientController::class, 'store']);
    Route::get('/clients/{client}', [ClientController::class, 'show']);
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit']);
    Route::patch('/clients/{client}', [ClientController::class, 'update']);
    Route::delete('/clients/{client}', [ClientController::class, 'destroy']); // excluir usuário
// });
