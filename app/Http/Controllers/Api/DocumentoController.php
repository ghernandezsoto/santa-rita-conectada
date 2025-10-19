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
        // Busca todos los documentos, los ordena por fecha de creación (los más nuevos primero)
        // y los devuelve en un formato JSON paginado.
        $documentos = Documento::latest()->paginate(15);

        return response()->json($documentos);
    }
}