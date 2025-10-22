<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Socio;
use Illuminate\View\View;

class AporteController extends Controller
{
    public function index(): View
    {
        // Obtener el usuario autenticado.
        $user = Auth::user();

        // Encontrar el registro de Socio correspondiente.
        $socio = Socio::where('email', $user->email)->first();

        // Preparar un array para los datos, con valores por defecto.
        $datosAportes = [
            'balancePersonal' => 0,
            'transacciones' => collect(), // Una colección vacía por si no hay socio.
        ];

        // Si se encuentra el socio, calcular sus datos financieros.
        if ($socio) {
            $ingresosPersonales = $socio->transacciones()->where('tipo', 'Ingreso')->sum('monto');
            $egresosPersonales = $socio->transacciones()->where('tipo', 'Egreso')->sum('monto');

            $datosAportes['balancePersonal'] = $ingresosPersonales - $egresosPersonales;
            // Obtenemos todas las transacciones, paginadas, para un historial completo.
            $datosAportes['transacciones'] = $socio->transacciones()->latest('fecha')->paginate(10);
        }

        // Devolver la nueva vista con los datos calculados.
        return view('portal.aportes.index', $datosAportes);
    }
}