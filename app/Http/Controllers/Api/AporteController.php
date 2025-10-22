<?php

namespace App\Http/Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Socio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AporteController extends Controller
{
    /**
     * Devuelve los datos financieros para el socio autenticado.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Se usa la relación directa ---
        // Ya no buscamos por email, usamos la relación que ya está cargada
        $socio = $user->socio;

        if (!$socio) {
            // Esto ahora solo debería ocurrir si el usuario es de la directiva
            // o si un socio recién creado aún no tiene su 'socio_id' asignado.
            return response()->json(['message' => 'No se encontró un perfil de socio asociado.'], 404);
        }

        // Calcula el balance personal
        $ingresosPersonales = $socio->transacciones()->where('tipo', 'Ingreso')->sum('monto');
        $egresosPersonales = $socio->transacciones()->where('tipo', 'Egreso')->sum('monto');
        $balancePersonal = $ingresosPersonales - $egresosPersonales;

        // Obtiene el historial de transacciones paginado
        $transacciones = $socio->transacciones()->latest('fecha')->paginate(20);

        // Devuelve todo en una única respuesta JSON
        return response()->json([
            'balance_personal' => $balancePersonal,
            'transacciones' => $transacciones,
        ]);
    }
}