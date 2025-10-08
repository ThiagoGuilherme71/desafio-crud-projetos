<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Linha Adicionada
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'jwt.session' => \App\Http\Middleware\JwtAuthMiddleware::class,
        ]);
        // Remover o middleware automático do Tymon JWT
        $middleware->remove(\Tymon\JWTAuth\Http\Middleware\Authenticate::class);

        // Configurar o redirecionamento padrão quando não autenticado
        $middleware->redirectGuestsTo(fn () => route('login_form'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
