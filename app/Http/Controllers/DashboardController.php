<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Transaccion;
use App\Models\Comunicado;
use App\Models\Evento;

use App\Models\Documento; // Se importa el modelo de Documento.
use App\Models\Acta;      // Se importa el modelo de Acta.

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(): View | RedirectResponse
    {
        $user = Auth::user();

        // 1. VERIFICAR SI EL USUARIO ES UN SOCIO
        if ($user->hasRole('Socio')) {
            // 1.1. Si es socio, verificar si necesita cambiar su contraseña.
            if (is_null($user->password_changed_at)) {
                return redirect()->route('profile.edit')
                                 ->with('info', 'Por tu seguridad, por favor actualiza tu contraseña para continuar.');
            }

            // 1.2. Si es un socio validado, se buscan todos sus datos para el panel.

            // Se obtienen los archivos generales para todos los socios.
            $datosSocio = [
                'ultimosDocumentos' => Documento::latest()->take(5)->get(),
                'ultimasActas' => Acta::latest()->take(5)->get(),
            ];

            // Se buscan los datos financieros específicos del socio.
            $socio = Socio::where('email', $user->email)->first();

            if ($socio) {
                $ingresosPersonales = $socio->transacciones()->where('tipo', 'Ingreso')->sum('monto');
                $egresosPersonales = $socio->transacciones()->where('tipo', 'Egreso')->sum('monto');

                // Se fusionan los datos financieros con los datos generales.
                $datosSocio['balancePersonal'] = $ingresosPersonales - $egresosPersonales;
                $datosSocio['ultimasTransacciones'] = $socio->transacciones()->latest('fecha')->take(5)->get();
            }

            return view('dashboard', $datosSocio);

        } else {
            // 2. SI NO ES SOCIO (ES DIRECTIVA), MUESTRA EL PANEL DE ADMINISTRACIÓN
            $totalSocios = Socio::count();
            $ingresos = Transaccion::where('tipo', 'Ingreso')->sum('monto');
            $egresos = Transaccion::where('tipo', 'Egreso')->sum('monto');
            $balance = $ingresos - $egresos;
            $comunicadosRecientes = Comunicado::where('created_at', '>=', now()->subDays(30))->count();
            $proximosEventos = Evento::where('fecha_evento', '>=', now())->count();
            $ultimosSocios = Socio::latest()->take(5)->get();

            return view('dashboard', compact(
                'totalSocios',
                'balance',
                'ultimosSocios',
                'comunicadosRecientes',
                'proximosEventos'
            ));
        }
    }
}