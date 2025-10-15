<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Subsidio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubsidioController extends Controller
{
    public function index()
    {
        $subsidios = Subsidio::with('socio', 'user')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('subsidios.index', compact('subsidios'));
    }

    public function create()
    {
        $socios = Socio::orderBy('nombre')->get();
        return view('subsidios.create', compact('socios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'socio_id' => 'required|exists:socios,id',
            'nombre_subsidio' => 'required|string|max:255',
            'monto_solicitado' => 'required|numeric|min:0',
            'descripcion' => 'required|string',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Validación del archivo
        ]);

        $filePath = null;
        if ($request->hasFile('archivo')) {
            // Guarda el archivo en storage/app/public/subsidios y obtiene la ruta
            $filePath = $request->file('archivo')->store('subsidios', 'public');
        }

        Subsidio::create([
            'socio_id' => $request->socio_id,
            'nombre_subsidio' => $request->nombre_subsidio,
            'monto_solicitado' => $request->monto_solicitado,
            'descripcion' => $request->descripcion,
            'archivo_path' => $filePath, // Guarda la ruta en la BD
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('subsidios.index')
                         ->with('success', '¡Postulación a subsidio registrada exitosamente!');
    }

    public function show(Subsidio $subsidio)
    {
        // Este método puede usarse en el futuro si se necesita una vista de solo lectura.
    }

    public function edit(Subsidio $subsidio)
    {
        return view('subsidios.edit', compact('subsidio'));
    }

    public function update(Request $request, Subsidio $subsidio)
    {
        $request->validate([
            'estado' => 'required|in:Postulando,Aprobado,Rechazado,Finalizado',
            'observaciones_directiva' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Validación para el nuevo archivo
        ]);

        $data = $request->only(['estado', 'observaciones_directiva']);

        if ($request->hasFile('archivo')) {
            // Borra el archivo antiguo si existe
            if ($subsidio->archivo_path) {
                Storage::disk('public')->delete($subsidio->archivo_path);
            }
            // Guarda el nuevo archivo y actualiza la ruta
            $data['archivo_path'] = $request->file('archivo')->store('subsidios', 'public');
        }

        $subsidio->update($data);

        return redirect()->route('subsidios.index')
                         ->with('success', 'El estado de la postulación ha sido actualizado.');
    }

    public function destroy(Subsidio $subsidio)
    {
        // Borra el archivo asociado del almacenamiento si existe
        if ($subsidio->archivo_path) {
            Storage::disk('public')->delete($subsidio->archivo_path);
        }

        $subsidio->delete();

        return redirect()->route('subsidios.index')
                         ->with('success', 'Postulación a subsidio eliminada exitosamente.');
    }
}