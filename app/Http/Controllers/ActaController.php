<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Por ahora, solo obtendremos las actas. Más adelante añadiremos paginación.
        $actas = Acta::orderBy('fecha', 'desc')->get(); // Ordenar por fecha, la más reciente primero

        // Pasamos la variable $actas a la vista 'actas.index'
        return view('actas.index', compact('actas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Muestra la vista con el formulario para subir una nueva acta.
        return view('actas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos del formulario, incluyendo el archivo.
        $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date',
            'contenido' => 'required|string',
            'archivo' => 'required|file|mimes:pdf|max:2048', // Requerido, debe ser PDF, máx 2MB
        ]);

        // 2. Manejar la subida del archivo PDF.
        $filePath = $request->file('archivo')->store('actas', 'public');

        // 3. Crear el registro en la base de datos.
        Acta::create([
            'titulo' => $request->titulo,
            'fecha' => $request->fecha,
            'contenido' => $request->contenido,
            'archivo_path' => $filePath,
            'user_id' => auth()->id(), // Asigna el ID del usuario autenticado
        ]);

        // 4. Redirigir a la lista de actas con un mensaje de éxito.
        return redirect()->route('actas.index')
                        ->with('success', '¡Acta subida y registrada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Acta $acta)
    {
        // Verifica si el archivo existe en el almacenamiento
        if (!Storage::disk('public')->exists($acta->archivo_path)) {
            // Si no existe, redirige de vuelta con un mensaje de error.
            return redirect()->route('actas.index')->with('error', 'El archivo del acta no fue encontrado.');
        }

        // Si existe, devuelve el archivo para que el navegador lo muestre.
        return Storage::disk('public')->response($acta->archivo_path);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Acta $acta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Acta $acta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acta $acta)
    {
        // 1. Eliminar el archivo físico del almacenamiento.
        if (Storage::disk('public')->exists($acta->archivo_path)) {
            Storage::disk('public')->delete($acta->archivo_path);
        }

        // 2. Eliminar el registro del acta de la base de datos.
        $acta->delete();

        // 3. Redirigir con un mensaje de éxito.
        return redirect()->route('actas.index')
                        ->with('success', 'Acta eliminada exitosamente.');
    }
}
