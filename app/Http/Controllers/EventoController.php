<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::orderBy('fecha_evento', 'asc')->paginate(10);
        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        return view('eventos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_evento' => 'required|date',
            'lugar' => 'nullable|string|max:255',
        ]);

        Evento::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_evento' => $request->fecha_evento,
            'lugar' => $request->lugar,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('eventos.index')
                         ->with('success', '¡Evento creado exitosamente!');
    }

    public function edit(Evento $evento)
    {
        return view('eventos.edit', compact('evento'));
    }

    public function update(Request $request, Evento $evento)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_evento' => 'required|date',
            'lugar' => 'nullable|string|max:255',
        ]);

        $evento->update($request->all());

        return redirect()->route('eventos.index')
                         ->with('success', '¡Evento actualizado exitosamente!');
    }

    /**
     * Elimina un evento de la base de datos.
     */
    public function destroy(Evento $evento)
    {
        $evento->delete();

        return redirect()->route('eventos.index')
                         ->with('success', '¡Evento eliminado exitosamente!');
    }

    // El método show lo podemos dejar vacío por ahora
    public function show(Evento $evento){}
}