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

        // Usa la relación directa ---

        $socio = $user->socio;


        // Prepara un arreglo para los datos
        $datosAportes = [
            'balancePersonal' => 0,
            'transacciones' => collect(), // una colección vacía en caso de que no existan socios
        ];

        // Si el socio existe, calcular la información financiera.
        if ($socio) {
            $ingresosPersonales = $socio->transacciones()->where('tipo', 'Ingreso')->sum('monto');
            $egresosPersonales = $socio->transacciones()->where('tipo', 'Egreso')->sum('monto');

            $datosAportes['balancePersonal'] = $ingresosPersonales - $egresosPersonales;
            // Get all transactions, paginated, for a complete history.
            $datosAportes['transacciones'] = $socio->transacciones()->latest('fecha')->paginate(10);
        }

        return view('portal.aportes.index', $datosAportes);
    }
}