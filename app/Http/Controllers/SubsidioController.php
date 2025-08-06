<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Subsidio;
use Illuminate\Http\Request;

class SubsidioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Usamos with() para cargar las relaciones y evitar consultas N+1 (más eficiente).
        $subsidios = Subsidio::with('socio', 'user')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('subsidios.index', compact('subsidios'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtenemos todos los socios para poder seleccionarlos en un dropdown.
        $socios = Socio::orderBy('nombre')->get();

        return view('subsidios.create', compact('socios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validamos los datos del formulario.
        $request->validate([
            'socio_id' => 'required|exists:socios,id',
            'nombre_subsidio' => 'required|string|max:255',
            'monto_solicitado' => 'required|numeric|min:0',
            'descripcion' => 'required|string',
        ]);

        // 2. Creamos la postulación en la base de datos.
        Subsidio::create([
            'socio_id' => $request->socio_id,
            'nombre_subsidio' => $request->nombre_subsidio,
            'monto_solicitado' => $request->monto_solicitado,
            'descripcion' => $request->descripcion,
            'user_id' => auth()->id(), // Asigna el ID del miembro de la directiva que la registra.
        ]);

        // 3. Redirigimos a la lista con un mensaje de éxito.
        return redirect()->route('subsidios.index')
                        ->with('success', '¡Postulación a subsidio registrada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subsidio $subsidio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subsidio $subsidio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subsidio $subsidio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subsidio $subsidio)
    {
        //
    }
}
