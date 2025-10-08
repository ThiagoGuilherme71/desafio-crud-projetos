<?php

use App\Http\Controllers\{
    HomeController,
    AuthController
};
use Illuminate\Support\Facades\Route;

// Rotas protegidas
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Home
    Route::get('/', [HomeController::class, 'home'])->name('home');
});
