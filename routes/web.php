<?php

use App\Http\Controllers\{AuthController, HomeController};
use Illuminate\Support\Facades\Route;

// Rotas de formulários (GET - exibir formulários)
Route::get('/login', [AuthController::class, 'login_form'])->name('login_form');
Route::get('/register', [AuthController::class, 'register_form'])->name('register_form');

// Rotas de processamento (POST - processar formulários)
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Rotas protegidas (web - usando JWT)
Route::middleware('jwt.session')->group(function () {
    Route::get('/home', [HomeController::class, 'home'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout_web'])->name('logout');
});
