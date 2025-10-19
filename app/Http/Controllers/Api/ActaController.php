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
        // Busca todas las actas, las ordena por fecha de creación (las más nuevas primero)
        // y las devuelve en un formato JSON paginado.
        $actas = Acta::latest()->paginate(15);

        return response()->json($actas);
    }
}