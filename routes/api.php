<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
// --- IMPORTAMOS LOS NUEVOS CONTROLADORES DE LA API ---
use App\Http\Controllers\Api\ComunicadoController;
use App\Http\Controllers\Api\EventoController;

// --- RUTA DE LOGIN PARA LA API ---
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required', // ej: "Mi Telefono Android"
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Las credenciales proporcionadas son incorrectas.'],
        ]);
    }

    $token = $user->createToken($request->device_name)->plainTextToken;

    return response()->json(['token' => $token]);
});

// --- RUTAS PROTEGIDAS POR SANCTUM ---
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para obtener datos del usuario logueado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas para Comunicados (solo lectura)
    Route::get('/comunicados', [ComunicadoController::class, 'index']);
    Route::get('/comunicados/{comunicado}', [ComunicadoController::class, 'show']);

    // Rutas para Eventos (solo lectura)
    Route::get('/eventos', [EventoController::class, 'index']);
    Route::get('/eventos/{evento}', [EventoController::class, 'show']);
    
    // Ruta para cerrar sesión en la API
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    });
});