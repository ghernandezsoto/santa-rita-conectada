<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Transaccion;
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

        // --- DATOS PARA LAS LISTAS RÁPIDAS ---
        $ultimosSocios = Socio::latest()->take(5)->get(); // Obtiene los últimos 5 socios registrados

        return view('dashboard', compact(
            'totalSocios',
            'balance',
            'ultimosSocios'
        ));
    }
}