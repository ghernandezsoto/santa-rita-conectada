<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Comunicado;
use Illuminate\Http\Request;

class ComunicadoController extends Controller
{
    /**
     * Muestra una lista de los comunicados enviados.
     */
    public function index()
    {
        // Buscamos solo los comunicados que ya han sido enviados (no borradores)
        // y los ordenamos por el más reciente.
        $comunicados = Comunicado::whereNotNull('fecha_envio')
                                 ->latest('fecha_envio')
                                 ->paginate(10);

        // Devolvemos la vista del portal con los comunicados.
        return view('portal.comunicados.index', compact('comunicados'));
    }

    /**
     * Muestra un único comunicado.
     */
    public function show(Comunicado $comunicado)
    {
        if (!$comunicado->fecha_envio) {
            abort(404);
        }

        return view('portal.comunicados.show', compact('comunicado'));
    }
}