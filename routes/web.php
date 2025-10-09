<?php

use App\Http\Controllers\{
    AuthController,
    HomeController,
    ProjetosController
};
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::get('/login', [AuthController::class, 'login_form'])->name('login_form');
Route::get('/register', [AuthController::class, 'register_form'])->name('register_form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Rotas protegidas com JWT
Route::middleware(['jwt.session'])->group(function () {
    // Home
    // The home route is now '/', but '/home' is kept for backward compatibility.
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/home', [HomeController::class, 'home']);

    // CRUD de Projetos
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/data', [ProjetosController::class, 'getProjectsData'])->name('data');
        Route::get('/', [ProjetosController::class, 'index'])->name('index');
        Route::get('/create', [ProjetosController::class, 'create'])->name('create');
        Route::post('/', [ProjetosController::class, 'store'])->name('store');
        Route::get('/{id}', [ProjetosController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProjetosController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProjetosController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProjetosController::class, 'destroy'])->name('destroy');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout_web'])->name('logout');
});
