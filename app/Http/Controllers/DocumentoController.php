<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::with('user')->orderBy('fecha_documento', 'desc')->paginate(15);
        return view('documentos.index', compact('documentos'));
    }

    public function create()
    {
        return view('documentos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_documento' => 'required|string|max:255',
            'fecha_documento' => 'required|date',
            'descripcion' => 'nullable|string',
            'archivo' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:5120',
        ]);

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

    /**
     * Permite la descarga del archivo asociado.
     */
    public function show(Documento $documento)
    {
        if (!Storage::disk('public')->exists($documento->archivo_path)) {
            return redirect()->route('documentos.index')->with('error', 'El archivo no fue encontrado y no se puede descargar.');
        }

        return Storage::disk('public')->download($documento->archivo_path);
    }

    /**
     * Elimina el documento y su archivo físico.
     */
    public function destroy(Documento $documento)
    {
        if (Storage::disk('public')->exists($documento->archivo_path)) {
            Storage::disk('public')->delete($documento->archivo_path);
        }

        $documento->delete();

        return redirect()->route('documentos.index')
                         ->with('success', '¡Documento eliminado exitosamente!');
    }
    

    public function edit(Documento $documento){}
    public function update(Request $request, Documento $documento){}
}