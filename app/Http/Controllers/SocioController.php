<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;

class SocioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Busca TODOS los socios en la base de datos.
        $socios = Socio::orderBy('nombre')->get(); // Los ordenamos por nombre

        // 2. Envía la variable $socios a la vista.
        return view('socios.index', compact('socios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Muestra la vista que contendrá el formulario de creación.
        return view('socios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos del formulario que se envía.
        $request->validate([
            'rut' => 'required|string|unique:socios,rut',
            'nombre' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
            'fecha_ingreso' => 'required|date',
            'profesion' => 'nullable|string|max:255',
            'edad' => 'nullable|integer|min:0',
            'estado_civil' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:socios,email',
            'observaciones' => 'nullable|string',
        ]);

        // 2. Si la validación pasa, crea el nuevo socio en la base de datos.
        Socio::create($request->all());

        // 3. Redirige al usuario de vuelta a la lista de socios con un mensaje de éxito.
        return redirect()->route('socios.index')
                         ->with('success', '¡Socio agregado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Socio $socio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Socio $socio)
    {
        // Muestra la vista de edición y le pasa el socio específico que se quiere editar.
        return view('socios.edit', compact('socio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Socio $socio)
    {
        // 1. Validar los datos (similar a store, pero permitiendo que el RUT y email actuales no den error de "único")
        $request->validate([
            'rut' => 'required|string|unique:socios,rut,' . $socio->id,
            'nombre' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
            'fecha_ingreso' => 'required|date',
            'profesion' => 'nullable|string|max:255',
            'edad' => 'nullable|integer|min:0',
            'estado_civil' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:socios,email,' . $socio->id,
            'observaciones' => 'nullable|string',
            'estado' => 'required|string', // Añadimos la validación para el estado
        ]);

        // 2. Actualiza el socio en la base de datos con los nuevos datos.
        $socio->update($request->all());

        // 3. Redirige a la lista con un mensaje de éxito.
        return redirect()->route('socios.index')
                        ->with('success', '¡Socio actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Socio $socio)
    {
        // Elimina el socio de la base de datos.
        $socio->delete();

        // Redirige a la lista con un mensaje de éxito.
        return redirect()->route('socios.index')
                        ->with('success', 'Socio eliminado exitosamente.');
    }
}