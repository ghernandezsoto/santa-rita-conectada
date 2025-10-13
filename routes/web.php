<?php

use App\Http\Controllers\Portal\ComunicadoController as PortalComunicadoController;
use App\Http\Controllers\Portal\EventoController as PortalEventoController;

use App\Exports\TransaccionesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\ActaController;
use App\Http\Controllers\ComunicadoController;
use App\Http\Controllers\SubsidioController;
use App\Http\Controllers\TransaccionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\DocumentoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// --- GRUPO DE RUTAS PROTEGIDAS POR AUTENTICACIÓN ---
Route::middleware('auth')->group(function () {
    // Rutas de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Módulos protegidos
    Route::resource('socios', SocioController::class)
        ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Secretario|Presidente');

    Route::resource('actas', ActaController::class)
        ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Secretario|Presidente');

    Route::resource('comunicados', ComunicadoController::class)
        ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Secretario|Presidente');
    Route::post('/comunicados/{comunicado}/enviar', [ComunicadoController::class, 'enviar'])
        ->name('comunicados.enviar')
        ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Secretario|Presidente');

    Route::get('/transacciones/exportar', function () {
        return Excel::download(new TransaccionesExport, 'balance-tesoreria.xlsx');
    })->name('transacciones.exportar')->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Tesorero|Presidente');

    Route::resource('transacciones', TransaccionController::class)
        ->parameters(['transacciones' => 'transaccion'])
        ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Tesorero|Presidente');

    Route::resource('subsidios', SubsidioController::class)
        ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Presidente|Secretario|Tesorero');

    Route::resource('eventos', EventoController::class)
        ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Secretario|Presidente');

    // --- NUEVA RUTA PARA ARCHIVO DIGITAL ---
    Route::resource('documentos', DocumentoController::class)
        ->middleware(\Spatie\Permission\Middleware\RoleMiddleware::class . ':Presidente|Secretario|Tesorero');
});

// --- INICIO: RUTAS DEL PORTAL PARA SOCIOS ---
// Este grupo entero está protegido para que solo usuarios con el rol 'Socio' puedan acceder.
// --- INICIO DE LA MODIFICACIÓN ---
Route::middleware(['auth', 'role:Socio', 'password.changed'])->prefix('portal')->name('portal.')->group(function () {
// --- FIN DE LA MODIFICACIÓN ---

    // Rutas de solo lectura para Comunicados
    Route::get('/comunicados', [PortalComunicadoController::class, 'index'])->name('comunicados.index');
    Route::get('/comunicados/{comunicado}', [PortalComunicadoController::class, 'show'])->name('comunicados.show');

    // Rutas de solo lectura para Eventos
    Route::get('/eventos', [PortalEventoController::class, 'index'])->name('eventos.index');
    Route::get('/eventos/{evento}', [PortalEventoController::class, 'show'])->name('eventos.show');

});
// --- FIN: RUTAS DEL PORTAL PARA SOCIOS ---

require __DIR__.'/auth.php';