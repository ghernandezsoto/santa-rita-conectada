<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use Freshwork\ChileanBundle\Rut; // <-- Importar la clase Rut

class SocioController extends Controller
{
    public function index(Request $request)
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

    public function create()
    {
        $estadosCiviles = ['Soltero/a', 'Casado/a', 'Viudo/a', 'Divorciado/a', 'Conviviente Civil'];
        $profesiones = ['Dueña de Casa', 'Estudiante', 'Jubilado/a', 'Obrero/a', 'Técnico/a', 'Profesional', 'Agricultor/a', 'Otro'];
        return view('socios.create', compact('estadosCiviles', 'profesiones'));
    }

    public function store(Request $request)
    {
        // Limpiamos los datos antes de validar
        $request->merge([
            'rut' => Rut::parse($request->rut)->format(Rut::FORMAT_WITH_VERIFIER),
            'telefono' => preg_replace('/[^0-9]/', '', $request->telefono)
        ]);

        $request->validate([
            'rut' => 'required|string|cl_rut|unique:socios,rut', // <-- Nueva regla de validación
            'nombre' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
            'fecha_ingreso' => 'required|date',
            // Nueva regla para el teléfono: debe empezar con 569 y tener 11 dígitos en total
            'telefono' => 'nullable|string|regex:/^569\d{8}$/',
            'email' => 'nullable|email|unique:socios,email',
            // ... otras reglas sin cambios ...
            'profesion' => 'nullable|string|max:255',
            'edad' => 'nullable|integer|min:0',
            'estado_civil' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        Socio::create($request->all());

        return redirect()->route('socios.index')
                         ->with('success', '¡Socio agregado exitosamente!');
    }

    public function show(Socio $socio)
    {
        $socio->load(['transacciones' => function ($query) {
            $query->orderBy('fecha', 'desc');
        }]);
        return view('socios.show', compact('socio'));
    }

    public function edit(Socio $socio)
    {
        $estadosCiviles = ['Soltero/a', 'Casado/a', 'Viudo/a', 'Divorciado/a', 'Conviviente Civil'];
        $profesiones = ['Dueña de Casa', 'Estudiante', 'Jubilado/a', 'Obrero/a', 'Técnico/a', 'Profesional', 'Agricultor/a', 'Otro'];
        return view('socios.edit', compact('socio', 'estadosCiviles', 'profesiones'));
    }

    public function update(Request $request, Socio $socio)
    {
        // Limpiamos los datos antes de validar
        $request->merge([
            'rut' => Rut::parse($request->rut)->format(Rut::FORMAT_WITH_VERIFIER),
            'telefono' => preg_replace('/[^0-9]/', '', $request->telefono)
        ]);
        
        $request->validate([
            'rut' => 'required|string|cl_rut|unique:socios,rut,' . $socio->id, // <-- Nueva regla
            'nombre' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
            'fecha_ingreso' => 'required|date',
            'telefono' => 'nullable|string|regex:/^569\d{8}$/', // <-- Nueva regla
            'email' => 'nullable|email|unique:socios,email,' . $socio->id,
            'estado' => 'required|string',
            // ... otras reglas sin cambios ...
            'profesion' => 'nullable|string|max:255',
            'edad' => 'nullable|integer|min:0',
            'estado_civil' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $socio->update($request->all());

        return redirect()->route('socios.index')
                         ->with('success', '¡Socio actualizado exitosamente!');
    }

    public function destroy(Socio $socio)
    {
        $socio->delete();
        return redirect()->route('socios.index')
                         ->with('success', 'Socio eliminado exitosamente.');
    }
}