<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Acta;
use Illuminate\Http\Request;

class ActaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // --- INICIO DE LA MODIFICACIÓN ---
        // Se cambia paginate() por get() para devolver un array simple [ ... ]
        // que la aplicación móvil pueda interpretar correctamente.
        $actas = Acta::latest()->get();
        // --- FIN DE LA MODIFICACIÓN ---

        return response()->json($actas);
    }
}