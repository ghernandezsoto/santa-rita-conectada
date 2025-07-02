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
        // Por ahora, solo muestra la vista. Más adelante aquí listaremos los socios.
        return view('socios.index');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Socio $socio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Socio $socio)
    {
        //
    }
}