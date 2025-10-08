<?php

use App\Http\Controllers\{AuthController, HomeController};
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::get('/login', [AuthController::class, 'login_form'])->name('login_form');
Route::get('/register', [AuthController::class, 'register_form'])->name('register_form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Rotas protegidas com JWT
Route::middleware(['jwt.session'])->group(function () {
    Route::get('/home', [HomeController::class, 'home'])->name('home');
    Route::get('/projects/data', [HomeController::class, 'getProjectsData'])->name('projects.data');
    Route::post('/logout', [AuthController::class, 'logout_web'])->name('logout');
});
