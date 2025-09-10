<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comunicado;
use Illuminate\Http\Request;

class ComunicadoController extends Controller
{
    // Devuelve una lista de todos los comunicados
    public function index()
    {
        $comunicados = Comunicado::with('user:id,name') // Solo trae id y nombre del autor
                                     ->orderBy('created_at', 'desc')
                                     ->get();
        return response()->json($comunicados);
    }

    // Devuelve los detalles de un solo comunicado
    public function show(Comunicado $comunicado)
    {
        // --- CORRECCIÓN AQUÍ ---
        // Cargamos la relación del usuario antes de enviar la respuesta.
        $comunicado->load('user:id,name');
        
        return response()->json($comunicado);
    }
}