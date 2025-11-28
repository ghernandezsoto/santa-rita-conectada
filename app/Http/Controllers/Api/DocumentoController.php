<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

#aaaa
class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::latest()->get();

        $documentos->transform(function ($doc) {
            if ($doc->archivo_path) {
                $doc->archivo_path = URL::temporarySignedRoute(
                    'documentos.publico',
                    now()->addMinutes(180),
                    ['documento' => $doc->id]
                );
            }
            return $doc;
        });

        return response()->json($documentos);
    }
}