<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    /**
     * Devuelve una lista de todos los eventos.
     */
    public function index()
    {
        // Añadimos with() para incluir los datos del usuario en la respuesta
        $eventos = Evento::with('user:id,name')
                         ->orderBy('fecha_evento', 'asc')
                         ->get();

        return response()->json($eventos);
    }

    /**
     * Devuelve los detalles de un solo evento.
     */
    public function show(Evento $evento)
    {
        // También lo añadimos aquí por consistencia
        $evento->load('user:id,name');

        return response()->json($evento);
    }
}