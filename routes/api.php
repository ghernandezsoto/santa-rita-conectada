<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\ActaController;
use App\Http\Controllers\Api\AporteController;
use App\Http\Controllers\Api\ComunicadoController;
use App\Http\Controllers\Api\DocumentoController;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\FcmController;
use App\Models\Socio;
use App\Models\Transaccion;
use Carbon\Carbon;
use App\Models\Comunicado;
use App\Models\Evento;

use Illuminate\Support\Facades\Notification;

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

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        $user = $request->user();
        $user->load('roles');
        return $user;
    });

    Route::post('/fcm-token', [FcmController::class, 'register']);

    Route::get('/comunicados', [ComunicadoController::class, 'index']);
    Route::get('/comunicados/{comunicado}', [ComunicadoController::class, 'show']);

    Route::get('/eventos', [EventoController::class, 'index']);
    Route::get('/eventos/{evento}', [EventoController::class, 'show']);

    Route::get('/documentos', [DocumentoController::class, 'index']);
    Route::get('/actas', [ActaController::class, 'index']);

    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    });
    
    // Se mueve la ruta de aportes a su lugar correcto, fuera del closure de logout.
    Route::get('/aportes', [AporteController::class, 'index'])->middleware('role:Socio');


    // --- RUTA PARA LAS TARJETAS DE RESUMEN DE LA DIRECTIVA ---
    
    Route::get('/directivo/summary', function () {
        $totalSocios = Socio::count();
        $ingresos = Transaccion::where('tipo', 'Ingreso')->sum('monto');
        $egresos = Transaccion::where('tipo', 'Egreso')->sum('monto');
        $balance = $ingresos - $egresos;
        $comunicadosRecientes = Comunicado::where('created_at', '>=', now()->subDays(30))->count();
        $proximosEventos = Evento::where('fecha_evento', '>=', now())->count();

        return response()->json([
            'total_socios' => $totalSocios,
            'balance' => $balance,
            'comunicados_recientes' => $comunicadosRecientes,
            'proximos_eventos' => $proximosEventos,
        ]);
    })->middleware('role:Presidente|Secretario|Tesorero');

    // --- RUTA PARA LA LISTA DE SOCIOS (APP MÓVIL) ---
    Route::get('/directivo/socios', function () {
        // Obtenemos todos los socios, ordenados alfabéticamente por nombre
        $socios = Socio::orderBy('nombre')->get();
        return response()->json($socios);
    })->middleware('role:Presidente|Secretario|Tesorero');

    // --- RUTA PARA EL HISTORIAL DE TESORERÍA (APP MÓVIL) ---
    Route::get('/directivo/transacciones', function () {
        // Obtenemos todas las transacciones, ordenadas por fecha más reciente
        $transacciones = Transaccion::latest('fecha')->get();
        return response()->json($transacciones);
    })->middleware('role:Presidente|Secretario|Tesorero');


    // --- RUTA PARA CREAR UN COMUNICADO (APP MÓVIL) ---
    Route::post('/directivo/comunicados', function (Request $request) {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        $comunicado = Comunicado::create([
            'titulo' => $validated['titulo'],
            'contenido' => $validated['contenido'],
            'user_id' => $request->user()->id,
        ]);

        // Marcar como enviado (igual que en la web)
        $comunicado->update(['fecha_envio' => now()]);

        // Enviar por Email a los Socios Activos con Email
        //    (Usando el modelo Socio y la notificación de Email)
        $sociosParaEmail = Socio::whereRaw("LOWER(estado) = 'activo'")
                                ->whereNotNull('email')
                                ->get();

        if ($sociosParaEmail->isNotEmpty()) {
            // Usamos NuevoComunicadoNotification (modificada para solo enviar email)
            Notification::send($sociosParaEmail, new \App\Notifications\NuevoComunicadoNotification($comunicado));
            \Illuminate\Support\Facades\Log::info('[API Envio Email] Intentando enviar email a ' . $sociosParaEmail->count() . ' socios.');
        } else {
            \Illuminate\Support\Facades\Log::info('[API Envio Email] No se encontraron socios activos con email para notificar.');
        }

        // Enviar Notificación Push a Usuarios con Token FCM
        //    (Usando el modelo User y la notificación Push dedicada)
        $usuariosParaPush = User::role('Socio')
                                ->whereNotNull('fcm_token')
                                ->get();

        if ($usuariosParaPush->isNotEmpty()) {
            // Usamos PushComunicadoNotification (la que funciona desde la web)
            Notification::send($usuariosParaPush, new \App\Notifications\PushComunicadoNotification($comunicado));
            \Illuminate\Support\Facades\Log::info('[API Envio Push] Intentando enviar push a ' . $usuariosParaPush->count() . ' usuarios.');
        } else {
            \Illuminate\Support\Facades\Log::info('[API Envio Push] No se encontraron usuarios socios con fcm_token para notificar.');
        }


        return response()->json($comunicado, 201); // 201 = Creado Exitosamente
    })->middleware('role:Presidente|Secretario');

    // --- RUTA PARA EL GRÁFICO DE LA DIRECTIVA ---
    Route::get('/charts/finances', function () {
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i)->locale('es');
            $monthName = $date->translatedFormat('F');
            $year = $date->format('Y');
            $labels[] = ucfirst($monthName);
            $income = Transaccion::where('tipo', 'Ingreso')->whereYear('fecha', $year)->whereMonth('fecha', $date->month)->sum('monto');
            $incomeData[] = $income;
            $expense = Transaccion::where('tipo', 'Egreso')->whereYear('fecha', $year)->whereMonth('fecha', $date->month)->sum('monto');
            $expenseData[] = $expense;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Ingresos', 'data' => $incomeData, 'backgroundColor' => '#4ade80'],
                ['label' => 'Egresos', 'data' => $expenseData, 'backgroundColor' => '#f87171']
            ]
        ]);
    })->middleware('role:Presidente|Tesorero');

    // --- RUTA PARA EL GRÁFICO PERSONAL DEL SOCIO ---
    Route::get('/charts/personal-finances', function (Request $request) {
        $user = $request->user();
        $socio = Socio::where('email', $user->email)->first();

        if (!$socio) {
            return response()->json(['labels' => [], 'datasets' => []], 404);
        }

        $labels = [];
        $contributionData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i)->locale('es');
            $monthName = $date->translatedFormat('F');
            $year = $date->format('Y');
            $labels[] = ucfirst($monthName);
            $contribution = $socio->transacciones()->where('tipo', 'Ingreso')->whereYear('fecha', $year)->whereMonth('fecha', $date->month)->sum('monto');
            $contributionData[] = $contribution;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Mis Aportes',
                    'data' => $contributionData,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                    'tension' => 0.1
                ]
            ]
        ]);
    })->middleware('role:Socio');

});