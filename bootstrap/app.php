<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Configuramos las redirecciones globales
        $middleware->redirectTo(
            guests: '/login',    // Si no está logueado y quiere entrar a una ruta protegida
            users: '/principal'  // Si ya está logueado e intenta ir al login
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();