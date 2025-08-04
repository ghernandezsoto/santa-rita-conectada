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
        //
    })
    // --- SECCIÓN AÑADIDA ---
    // Aquí registramos manualmente los proveedores de servicios de paquetes.
    ->withProviders([
        Spatie\Permission\PermissionServiceProvider::class,
    ])
    // --- FIN DE LA SECCIÓN AÑADIDA ---
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();