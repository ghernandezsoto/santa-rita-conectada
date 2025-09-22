<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use Freshwork\ChileanBundle\Rut;
use App\Rules\ChileanPhone;
use Illuminate\Database\QueryException; // 1. LÍNEA AÑADIDA

class SocioController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Socio::query();

        if ($searchTerm) {
            $query->where('nombre', 'like', '%' . $searchTerm . '%')
                  ->orWhere('rut', 'like', '%' . Rut::parse($searchTerm)->normalize() . '%');
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
        $validated = $request->validate([
            'rut' => 'required|string|cl_rut|unique:socios,rut',
            'nombre' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
            'fecha_ingreso' => 'required|date',
            'telefono' => ['nullable', 'string', new ChileanPhone()],
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
            'rut' => 'required|string|cl_rut|unique:socios,rut,' . $socio->id,
            'nombre' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
            'fecha_ingreso' => 'required|date',
            'telefono' => ['nullable', 'string', new ChileanPhone()],
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

    // 2. MÉTODO MODIFICADO
    public function destroy(Socio $socio)
    {
        try {
            // Intenta eliminar al socio
            $socio->delete();
            // Si tiene éxito, redirige con un mensaje de éxito
            return redirect()->route('socios.index')
                             ->with('success', 'Socio eliminado exitosamente.');
        } catch (QueryException $e) {
            // Si falla, atrapa la excepción de la base de datos
            // El código '23000' es el error específico de violación de restricción
            if ($e->getCode() === '23000') {
                // Redirige hacia atrás con un mensaje de error específico y amigable
                return redirect()->back()
                                 ->with('error', 'No se puede eliminar este socio porque tiene registros asociados (como subsidios o transacciones). Por favor, elimine primero esos registros.');
            }
            // Para cualquier otro error de base de datos, mostramos un mensaje genérico
            return redirect()->back()
                             ->with('error', 'Ocurrió un error en la base de datos al intentar eliminar al socio.');
        }
    }
}