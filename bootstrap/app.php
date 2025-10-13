<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // --- INICIO DE LA MODIFICACIÓN ---
        // Se registran todos los alias necesarios para Spatie Permission
        // y nuestro nuevo middleware para forzar el cambio de contraseña.
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class, // <-- AÑADIDO
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class, // <-- AÑADIDO
            'password.changed' => \App\Http\Middleware\EnsurePasswordIsChanged::class, // <-- AÑADIDO
        ]);
        // --- FIN DE LA MODIFICACIÓN ---
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();