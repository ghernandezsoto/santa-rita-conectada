<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
// Usaremos la regla de validación de RUT del paquete
use Freshwork\ChileanBundle\Laravel\Validations\Rut;

class SocioController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Socio::query();

        if ($searchTerm) {
            // La búsqueda ahora funciona con RUTs formateados o sin formatear
            $query->where('nombre', 'like', '%' . $searchTerm . '%')
                  ->orWhere('rut', 'like', '%' . \Freshwork\ChileanBundle\Rut::parse($searchTerm)->normalize() . '%');
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
        // El validador ahora se encarga de todo. No necesitamos limpiar datos aquí.
        $validated = $request->validate([
            'rut' => ['required', 'string', new Rut(), 'unique:socios,rut'],
            'nombre' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
            'fecha_ingreso' => 'required|date',
            'telefono' => ['nullable', 'string', 'cl_phone'], // Regla de validación de teléfono chileno
            'email' => 'nullable|email|unique:socios,email',
            'profesion' => 'nullable|string|max:255',
            'edad' => 'nullable|integer|min:0',
            'estado_civil' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        Socio::create($validated);

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
        $validated = $request->validate([
            'rut' => ['required', 'string', new Rut(), 'unique:socios,rut,' . $socio->id],
            'nombre' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
            'fecha_ingreso' => 'required|date',
            'telefono' => ['nullable', 'string', 'cl_phone'], // Regla de validación de teléfono chileno
            'email' => 'nullable|email|unique:socios,email,' . $socio->id,
            'estado' => 'required|string',
            'profesion' => 'nullable|string|max:255',
            'edad' => 'nullable|integer|min:0',
            'estado_civil' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $socio->update($validated);

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