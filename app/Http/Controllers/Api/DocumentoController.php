<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL; 

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::latest()->get();

        // Transformamos cada documento para inyectar la URL firmada
        $documentos->transform(function ($doc) {
            if ($doc->archivo_path) {
                // Generamos una URL válida por 30 minutos
                $doc->archivo_path = URL::temporarySignedRoute(
                    'documentos.publico',
                    now()->addMinutes(30),
                    ['documento' => $doc->id]
                );
            }
            return $doc;
        });

        return response()->json($documentos);
    }
}