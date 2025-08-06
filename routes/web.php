<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\ActaController;
use App\Http\Controllers\ComunicadoController;
use App\Http\Controllers\TransaccionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// --- GRUPO DE RUTAS PROTEGIDAS POR AUTENTICACIÓN ---
Route::middleware('auth')->group(function () {
    // Rutas de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Módulo de Socios (AHORA PROTEGIDO CON EL NOMBRE COMPLETO)
    Route::resource('socios', SocioController::class)
         ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Secretario|Presidente');

    // Módulo de Actas
    Route::resource('actas', ActaController::class)
         ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Secretario|Presidente');

    // Módulo de Comunicados
    Route::resource('comunicados', ComunicadoController::class)
         ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Secretario|Presidente');
    Route::post('/comunicados/{comunicado}/enviar', [ComunicadoController::class, 'enviar'])
         ->name('comunicados.enviar')
         ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Secretario|Presidente');

    // Módulo de Tesorería
    Route::resource('transacciones', TransaccionController::class)
         ->parameters(['transacciones' => 'transaccion'])
         ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Tesorero|Presidente');
     
     // Módulo de Subsidios (solo para la directiva)
     Route::resource('subsidios', App\Http\Controllers\SubsidioController::class)->middleware('role:Presidente|Secretario|Tesorero');
});

require __DIR__.'/auth.php';