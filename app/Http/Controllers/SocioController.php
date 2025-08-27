<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;

class SocioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Añadimos Request para la búsqueda
    {
        $searchTerm = $request->input('search');
        $query = Socio::query();

        if ($searchTerm) {
            $query->where('nombre', 'like', '%' . $searchTerm . '%')
                  ->orWhere('rut', 'like', '%' . $searchTerm . '%');
        }

        $socios = $query->orderBy('nombre')->paginate(10);

        return view('socios.index', compact('socios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $estadosCiviles = ['Soltero/a', 'Casado/a', 'Viudo/a', 'Divorciado/a', 'Conviviente Civil'];
        $profesiones = ['Dueña de Casa', 'Estudiante', 'Jubilado/a', 'Obrero/a', 'Técnico/a', 'Profesional', 'Agricultor/a', 'Otro'];

        return view('socios.create', compact('estadosCiviles', 'profesiones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

        Socio::create($request->all());

        return redirect()->route('socios.index')
                         ->with('success', '¡Socio agregado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Socio $socio)
    {
        // Carga el socio específico junto con sus transacciones relacionadas,
        // ordenadas por la fecha más reciente.
        $socio->load(['transacciones' => function ($query) {
            $query->orderBy('fecha', 'desc');
        }]);

        // Pasa el socio (con sus transacciones ya cargadas) a la nueva vista 'socios.show'
        return view('socios.show', compact('socio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Socio $socio)
    {
        $estadosCiviles = ['Soltero/a', 'Casado/a', 'Viudo/a', 'Divorciado/a', 'Conviviente Civil'];
        $profesiones = ['Dueña de Casa', 'Estudiante', 'Jubilado/a', 'Obrero/a', 'Técnico/a', 'Profesional', 'Agricultor/a', 'Otro'];

        return view('socios.edit', compact('socio', 'estadosCiviles', 'profesiones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Socio $socio)
    {
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
            'estado' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        $socio->update($request->all());

        return redirect()->route('socios.index')
                         ->with('success', '¡Socio actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Socio $socio)
    {
        // Lógica para eliminar añadida aquí
        $socio->delete();

        return redirect()->route('socios.index')
                         ->with('success', 'Socio eliminado exitosamente.');
    }
}