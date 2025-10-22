<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentoController extends Controller
{
    public function show(Documento $documento): View
    {
        return view('portal.documentos.show', compact('documento'));
    }
}