<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActaController extends Controller
{
    public function index()
    {
        
        $actas = Acta::orderBy('fecha', 'desc')->get(); // Ordenar por fecha, la más reciente primero

        // Pasamos la variable $actas a la vista 'actas.index'
        return view('actas.index', compact('actas'));
    }

    public function create()
    {
        // Muestra la vista con el formulario para subir una nueva acta.
        return view('actas.create');
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario, incluyendo el archivo.
        $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date',
            'contenido' => 'required|string',
            'archivo' => 'required|file|mimes:pdf|max:20480', // Requerido, debe ser PDF, máx 2MB
        ]);

        // Manejar la subida del archivo PDF.
        $filePath = $request->file('archivo')->store('actas', 'public');

        // Crear el registro en la base de datos.
        Acta::create([
            'titulo' => $request->titulo,
            'fecha' => $request->fecha,
            'contenido' => $request->contenido,
            'archivo_path' => $filePath,
            'user_id' => auth()->id(), // Asigna el ID del usuario autenticado
        ]);

        // Redirigir a la lista de actas con un mensaje de éxito.
        return redirect()->route('actas.index')
                        ->with('success', '¡Acta subida y registrada exitosamente!');
    }

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


    public function edit(Acta $acta)
    {
        // Muestra el formulario de edición con los datos del acta seleccionada.
        return view('actas.edit', compact('acta'));
    }


    public function update(Request $request, Acta $acta)
    {
        // Validamos los datos. El título es único pero ignorando el acta actual.
        // El archivo es opcional, solo se valida si se sube uno nuevo.
        $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date',
            'contenido' => 'required|string',
            'archivo' => 'nullable|file|mimes:pdf|max:20480', // El archivo es opcional al editar
        ]);

        // Preparamos los datos para actualizar.
        $data = $request->only(['titulo', 'fecha', 'contenido']);

        // Si se subió un nuevo archivo...
        if ($request->hasFile('archivo')) {
            // ...borramos el archivo antiguo.
            if (Storage::disk('public')->exists($acta->archivo_path)) {
                Storage::disk('public')->delete($acta->archivo_path);
            }
            // ...guardamos el nuevo y actualizamos la ruta en los datos.
            $data['archivo_path'] = $request->file('archivo')->store('actas', 'public');
        }

        // Actualizamos el registro en la base de datos.
        $acta->update($data);

        // Redirigimos con un mensaje de éxito.
        return redirect()->route('actas.index')
                        ->with('success', '¡Acta actualizada exitosamente!');
    }

    public function destroy(Acta $acta)
    {
        // Eliminar el archivo físico del almacenamiento.
        if (Storage::disk('public')->exists($acta->archivo_path)) {
            Storage::disk('public')->delete($acta->archivo_path);
        }

        // Eliminar el registro del acta de la base de datos.
        $acta->delete();

        // Redirigir con un mensaje de éxito.
        return redirect()->route('actas.index')
                        ->with('success', 'Acta eliminada exitosamente.');
    }

    /**
     * Permite la descarga del archivo del acta para un Socio.
     * Esta ruta está aislada del 'show' de la Directiva.
     */
    public function descargarParaSocio(Acta $acta)
    {
        // Verifica si el archivo existe en el almacenamiento
        if (!Storage::disk('public')->exists($acta->archivo_path)) {
            // Si no existe, redirige de vuelta al ÍNDICE DEL PORTAL (que el Socio SÍ puede ver).
            return redirect()->route('portal.actas.index')->with('error', 'El archivo del acta no fue encontrado.');
        }

        // Si existe, devuelve el archivo para forzar la descarga.
        return Storage::disk('public')->download($acta->archivo_path);
    }

    public function descargarPublico(Request $request, Acta $acta)
    {
        // No requiere Auth::user() porque la firma valida el acceso.
        if (!Storage::disk('public')->exists($acta->archivo_path)) {
            abort(404, 'Archivo no encontrado.');
        }
        return Storage::disk('public')->download($acta->archivo_path);
    }
}
