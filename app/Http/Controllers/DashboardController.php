<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Transaccion;
use App\Models\Comunicado;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\View\View;
// --- INICIO DE LA MODIFICACIÓN ---
use Illuminate\Support\Facades\Auth; // Se importa para obtener el usuario autenticado.
use Illuminate\Http\RedirectResponse; // Se importa para el tipado de la redirección.
// --- FIN DE LA MODIFICACIÓN ---

class DashboardController extends Controller
{
    // --- INICIO DE LA MODIFICACIÓN ---
    // Se ajusta el tipado de retorno para que acepte una Vista o una Redirección.
    public function index(): View | RedirectResponse
    {
        $user = Auth::user();

        // 1. VERIFICAR SI EL USUARIO ES UN SOCIO
        if ($user->hasRole('Socio')) {
            // 1.1. Si es socio, verificar si necesita cambiar su contraseña.
            if (is_null($user->password_changed_at)) {
                // Si la contraseña es temporal, lo redirige forzosamente a la página
                // de perfil ANTES de mostrarle cualquier panel.
                return redirect()->route('profile.edit')
                                 ->with('info', 'Por tu seguridad, por favor actualiza tu contraseña para continuar.');
            }

            // 1.2. Si es un socio con la contraseña ya cambiada, muestra su panel.
            // (En el futuro, aquí podemos pasarle datos específicos para el socio)
            return view('dashboard');

        } else {
            // 2. SI NO ES SOCIO (ES DIRECTIVA), MUESTRA EL PANEL DE ADMINISTRACIÓN
            // Aquí se mantiene toda tu lógica original para las tarjetas de resumen.

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
    // --- FIN DE LA MODIFICACIÓN ---
}