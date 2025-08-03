<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('socios', App\Http\Controllers\SocioController::class);
    Route::resource('actas', App\Http\Controllers\ActaController::class);
    Route::resource('comunicados', App\Http\Controllers\ComunicadoController::class);
    Route::post('/comunicados/{comunicado}/enviar', [App\Http\Controllers\ComunicadoController::class, 'enviar'])->name('comunicados.enviar');
    Route::resource('transacciones', App\Http-Controllers\TransaccionController::class);
});

require __DIR__.'/auth.php';
