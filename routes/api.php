<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
// --- IMPORTAMOS LOS CONTROLADORES DE LA API ---
use App\Http\Controllers\Api\ComunicadoController;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\FcmController; 

use App\Models\Transaccion;
use Carbon\Carbon;

// --- RUTA DE LOGIN PARA LA API ---
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
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
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // --- RUTA PARA REGISTRAR EL TOKEN DE FCM ---
    Route::post('/fcm-token', [FcmController::class, 'register']);

    Route::get('/comunicados', [ComunicadoController::class, 'index']);
    Route::get('/comunicados/{comunicado}', [ComunicadoController::class, 'show']);

    Route::get('/eventos', [EventoController::class, 'index']);
    Route::get('/eventos/{evento}', [EventoController::class, 'show']);
    
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    });

    Route::get('/charts/finances', function () {
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            // Se establece el locale a español para los nombres de los meses
            $date = Carbon::now()->subMonths($i)->locale('es');
            
            // Se obtiene el nombre del mes y el año
            $monthName = $date->translatedFormat('F'); // Ej: "octubre"
            $year = $date->format('Y');

            // Se añade la etiqueta al array, con la primera letra en mayúscula
            $labels[] = ucfirst($monthName);

            // Se calcula la suma de ingresos para ese mes y año
            $income = Transaccion::where('tipo', 'Ingreso')
                ->whereYear('fecha', $year)
                ->whereMonth('fecha', $date->month)
                ->sum('monto');
            $incomeData[] = $income;

            // Se calcula la suma de egresos para ese mes y año
            $expense = Transaccion::where('tipo', 'Egreso')
                ->whereYear('fecha', $year)
                ->whereMonth('fecha', $date->month)
                ->sum('monto');
            $expenseData[] = $expense;
        }

        // Se devuelve el JSON con el formato que Chart.js espera
        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Ingresos',
                    'data' => $incomeData,
                    'backgroundColor' => '#4ade80', // Verde
                ],
                [
                    'label' => 'Egresos',
                    'data' => $expenseData,
                    'backgroundColor' => '#f87171', // Rojo
                ]
            ]
        ]);
    })->middleware('role:Presidente|Tesorero');
});