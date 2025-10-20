<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // --- INICIO DE LA MODIFICACIÓN ---
        // Se cambia paginate() por get() para devolver un array simple [ ... ]
        // en lugar de un objeto de paginación { ... }, solucionando el error en la app.
        $documentos = Documento::latest()->get();
        // --- FIN DE LA MODIFICACIÓN ---

        return response()->json($documentos);
    }
}