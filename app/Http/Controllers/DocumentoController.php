<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- Importar Storage

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::with('user')->orderBy('fecha_documento', 'desc')->paginate(15);
        return view('documentos.index', compact('documentos'));
    }

    /**
     * Muestra el formulario para subir un nuevo documento.
     */
    public function create()
    {
        return view('documentos.create');
    }

    /**
     * Guarda el nuevo documento y su información.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_documento' => 'required|string|max:255',
            'fecha_documento' => 'required|date',
            'descripcion' => 'nullable|string',
            'archivo' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:5120', // Máximo 5MB
        ]);

        // Guardar el archivo en storage/app/public/documentos_importantes
        $filePath = $request->file('archivo')->store('documentos_importantes', 'public');

        Documento::create([
            'nombre_documento' => $request->nombre_documento,
            'descripcion' => $request->descripcion,
            'fecha_documento' => $request->fecha_documento,
            'archivo_path' => $filePath,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('documentos.index')
                         ->with('success', '¡Documento subido exitosamente!');
    }

    // ... (los otros métodos los dejaremos para después)
    public function show(Documento $documento){}
    public function edit(Documento $documento){}
    public function update(Request $request, Documento $documento){}
    public function destroy(Documento $documento){}
}