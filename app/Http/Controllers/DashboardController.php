<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Transaccion;
use App\Models\Comunicado;
use App\Models\Evento; // <-- 1. IMPORTAR EL MODELO DE EVENTOS
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // --- DATOS PARA LAS TARJETAS DE RESUMEN ---
        $totalSocios = Socio::count();
        $ingresos = Transaccion::where('tipo', 'Ingreso')->sum('monto');
        $egresos = Transaccion::where('tipo', 'Egreso')->sum('monto');
        $balance = $ingresos - $egresos;
        $comunicadosRecientes = Comunicado::where('created_at', '>=', now()->subDays(30))->count();

        // --- NUEVA LÓGICA PARA EVENTOS ---
        $proximosEventos = Evento::where('fecha_evento', '>=', now())->count(); // <-- 2. AÑADIR CÁLCULO

        // --- DATOS PARA LAS LISTAS RÁPIDAS ---
        $ultimosSocios = Socio::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalSocios',
            'balance',
            'ultimosSocios',
            'comunicadosRecientes',
            'proximosEventos' // <-- 3. PASAR LA NUEVA VARIABLE A LA VISTA
        ));
    }
}