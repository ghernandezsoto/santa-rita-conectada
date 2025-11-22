<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaccion; 
use App\Models\Socio;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

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

    public function descargarComprobante(Transaccion $transaccion)
    {
        // 1. Seguridad: Verificar que la transacción pertenezca al socio logueado
        $user = Auth::user();
        if ($transaccion->socio_id !== $user->socio->id) {
            abort(403, 'No tienes permiso para ver este comprobante.');
        }

        // 2. Verificar si tiene comprobante adjunto
        if (!$transaccion->comprobante_path || !Storage::disk('public')->exists($transaccion->comprobante_path)) {
            return back()->with('error', 'Esta transacción no tiene un comprobante adjunto.');
        }

        // 3. Descargar
        return Storage::disk('public')->download($transaccion->comprobante_path);
    }

    /**
     * Descarga para URLs firmadas (Android/External).
     * No requiere Auth::user(), solo valida que la firma sea correcta (middleware 'signed').
     */
    public function descargarPublico(Transaccion $transaccion)
    {
        // Como la URL está firmada criptográficamente, sabemos que fue generada por nuestra API.
        // Solo validamos que el archivo físico exista.
        
        if (!$transaccion->comprobante_path || !Storage::disk('public')->exists($transaccion->comprobante_path)) {
            abort(404, 'El archivo ya no existe o fue movido.');
        }

        return Storage::disk('public')->download($transaccion->comprobante_path);
    }
}