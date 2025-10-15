<?php

use App\Http\Controllers\Portal\ComunicadoController as PortalComunicadoController;
use App\Http\Controllers\Portal\EventoController as PortalEventoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\ActaController;
use App\Http\Controllers\ComunicadoController;
use App\Http\Controllers\SubsidioController;
use App\Http\Controllers\TransaccionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\DocumentoController;
use App\Exports\TransaccionesExport;
use App\Models\Documento;
use App\Models\Acta;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// --- GRUPO DE RUTAS PROTEGIDAS POR AUTENTICACIÓN (DIRECTIVA) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('socios', SocioController::class)->middleware('role:Secretario|Presidente');
    Route::resource('actas', ActaController::class)->middleware('role:Secretario|Presidente');
    Route::resource('comunicados', ComunicadoController::class)->middleware('role:Secretario|Presidente');
    Route::post('/comunicados/{comunicado}/enviar', [ComunicadoController::class, 'enviar'])->name('comunicados.enviar')->middleware('role:Secretario|Presidente');
    Route::get('/transacciones/exportar', function () {
        return Excel::download(new TransaccionesExport, 'balance-tesoreria.xlsx');
    })->name('transacciones.exportar')->middleware('role:Tesorero|Presidente');
    Route::resource('transacciones', TransaccionController::class)->parameters(['transacciones' => 'transaccion'])->middleware('role:Tesorero|Presidente');
    Route::resource('subsidios', SubsidioController::class)->middleware('role:Presidente|Secretario|Tesorero');
    Route::resource('eventos', EventoController::class)->middleware('role:Secretario|Presidente');
    Route::resource('documentos', DocumentoController::class)->middleware('role:Presidente|Secretario|Tesorero');
});

// --- RUTAS DEL PORTAL PARA SOCIOS ---
Route::middleware(['auth', 'role:Socio', 'password.changed'])->prefix('portal')->name('portal.')->group(function () {

    Route::get('/documentos', function () {
        $documentos = Documento::latest()->paginate(10);
        return view('portal.documentos.index', compact('documentos'));
    })->name('documentos.index');

    // Se añade la ruta para ver un documento individual.
    Route::get('/documentos/{documento}', function (Documento $documento) {
        return view('portal.documentos.show', compact('documento'));
    })->name('documentos.show');

    Route::get('/actas', function () {
        $actas = Acta::latest()->paginate(10);
        return view('portal.actas.index', compact('actas'));
    })->name('actas.index');

    // Se añade la ruta para ver un acta individual.
    Route::get('/actas/{acta}', function (Acta $acta) {
        return view('portal.actas.show', compact('acta'));
    })->name('actas.show');

    Route::get('/comunicados', [PortalComunicadoController::class, 'index'])->name('comunicados.index');
    Route::get('/comunicados/{comunicado}', [PortalComunicadoController::class, 'show'])->name('comunicados.show');

    Route::get('/eventos', [PortalEventoController::class, 'index'])->name('eventos.index');
    Route::get('/eventos/{evento}', [PortalEventoController::class, 'show'])->name('eventos.show');

});

require __DIR__.'/auth.php';