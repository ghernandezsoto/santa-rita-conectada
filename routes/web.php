<?php

use App\Http\Controllers\ProfileController;
// Importamos los controladores para que el código sea más limpio
use App\Http\Controllers\SocioController;
use App\Http\Controllers\ActaController;
use App\Http\Controllers\ComunicadoController;
use App\Http\Controllers\TransaccionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Módulo de Socios (sin cambios)
    Route::resource('socios', App\Http\Controllers\SocioController::class);
    
    // Módulos protegidos usando el NOMBRE COMPLETO del middleware
    Route::resource('actas', App\Http\Controllers\ActaController::class)
        ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Secretario|Presidente');

    Route::resource('comunicados', App\Http\Controllers\ComunicadoController::class)
        ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Secretario|Presidente');
        
    Route::post('/comunicados/{comunicado}/enviar', [App\Http\Controllers\ComunicadoController::class, 'enviar'])
        ->name('comunicados.enviar')
        ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Secretario|Presidente');

    Route::resource('transacciones', App\Http\Controllers\TransaccionController::class)
        ->parameters(['transacciones' => 'transaccion'])
        ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Tesorero|Presidente');
});

require __DIR__.'/auth.php';