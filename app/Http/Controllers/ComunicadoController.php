<?php

namespace App\Http\Controllers;

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comunicado $comunicado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comunicado $comunicado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comunicado $comunicado)
    {
        //
    }
}
