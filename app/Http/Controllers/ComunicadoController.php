<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Notifications\NuevoComunicadoNotification;
use Illuminate\Support\Facades\Notification;

use App\Models\Comunicado;
use Illuminate\Http\Request;

class ComunicadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comunicados = Comunicado::with('user')->orderBy('created_at', 'desc')->get();

        return view('comunicados.index', compact('comunicados'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Muestra la vista con el formulario para crear un nuevo comunicado.
        return view('comunicados.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos del formulario.
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        // 2. Crear el registro en la base de datos.
        Comunicado::create([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'user_id' => auth()->id(), // Asigna el ID del usuario que está creando el comunicado.
        ]);

        // 3. Redirigir a la lista de comunicados con un mensaje de éxito.
        return redirect()->route('comunicados.index')
                        ->with('success', '¡Comunicado creado exitosamente!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Comunicado $comunicado)
    {
        // Pasa el comunicado específico a una nueva vista llamada 'comunicados.show'.
        return view('comunicados.show', compact('comunicado'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comunicado $comunicado)
    {
        // Muestra el formulario de edición con los datos del comunicado seleccionado.
        return view('comunicados.edit', compact('comunicado'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comunicado $comunicado)
    {
        // 1. Validamos los datos.
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        // 2. Actualizamos el registro en la base de datos.
        $comunicado->update($request->all());

        // 3. Redirigimos a la lista con un mensaje de éxito.
        return redirect()->route('comunicados.index')
                        ->with('success', '¡Comunicado actualizado exitosamente!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comunicado $comunicado)
    {
        $comunicado->delete();

        return redirect()->route('comunicados.index')
                        ->with('success', 'Comunicado eliminado exitosamente.');
    }

    public function enviar(Comunicado $comunicado)
    {
    // 1. Verificar si ya fue enviado.
    if ($comunicado->fecha_envio) {
        return redirect()->route('comunicados.index')->with('error', 'Este comunicado ya fue enviado.');
    }

    // 2. Obtener todos los socios activos que tengan un email registrado.
    $sociosActivos = Socio::where('estado', 'Activo')->whereNotNull('email')->get();

    // 3. ¡CAMBIO CLAVE! Marcar el comunicado como enviado AHORA.
    // Esto actualiza la interfaz de inmediato.
    $comunicado->update(['fecha_envio' => now()]);

    // 4. Si hay socios a quienes notificar, enviar el trabajo a la cola.
    // Esto ocurrirá en segundo plano.
    if ($sociosActivos->isNotEmpty()) {
        Notification::send($sociosActivos, new NuevoComunicadoNotification($comunicado));
    }

    // 5. Redirigir con un mensaje de éxito.
    return redirect()->route('comunicados.index')
                     ->with('success', '¡El comunicado ha sido puesto en la cola para ser enviado a ' . $sociosActivos->count() . ' socios activos!');
    }
}
