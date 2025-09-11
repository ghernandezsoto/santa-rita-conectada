<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use Freshwork\ChileanBundle\Rut;

class SocioController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Socio::query();

        if ($searchTerm) {
            $cleanSearchTerm = preg_replace('/[^0-9Kk]/', '', $searchTerm);
            $query->where('nombre', 'like', '%' . $searchTerm . '%')
                  ->orWhere('rut', 'like', '%' . $cleanSearchTerm . '%');
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
        // --- CORRECCIÓN AQUÍ ---
        $request->merge([
            'rut' => Rut::parse($request->rut)->normalize(), // Usamos normalize()
            'telefono' => preg_replace('/[^0-9]/', '', $request->telefono)
        ]);

        $request->validate([
            'rut' => 'required|string|cl_rut|unique:socios,rut',
            'nombre' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
            'fecha_ingreso' => 'required|date',
            'telefono' => 'nullable|string|regex:/^569\d{8}$/',
            'email' => 'nullable|email|unique:socios,email',
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
        // --- CORRECCIÓN AQUÍ ---
        $request->merge([
            'rut' => Rut::parse($request->rut)->normalize(), // Usamos normalize()
            'telefono' => preg_replace('/[^0-9]/', '', $request->telefono)
        ]);
        
        $request->validate([
            'rut' => 'required|string|cl_rut|unique:socios,rut,' . $socio->id,
            'nombre' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
            'fecha_ingreso' => 'required|date',
            'telefono' => 'nullable|string|regex:/^569\d{8}$/',
            'email' => 'nullable|email|unique:socios,email,' . $socio->id,
            'estado' => 'required|string',
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