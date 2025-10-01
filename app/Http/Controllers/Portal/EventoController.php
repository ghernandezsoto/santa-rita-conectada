<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    /**
     * Muestra una lista de los próximos eventos.
     */
    public function index()
    {
        // Buscamos solo los eventos futuros (desde hoy en adelante)
        // y los ordenamos por el más próximo.
        $eventos = Evento::where('fecha_evento', '>=', now())
                         ->orderBy('fecha_evento', 'asc')
                         ->paginate(10);

        // Devolvemos la vista del portal con los eventos.
        return view('portal.eventos.index', compact('eventos'));
    }

    /**
     * Muestra un único evento.
     */
    public function show(Evento $evento)
    {
        return view('portal.eventos.show', compact('evento'));
    }
}