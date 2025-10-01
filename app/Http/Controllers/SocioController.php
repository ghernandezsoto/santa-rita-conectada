<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use Freshwork\ChileanBundle\Rut;
use App\Rules\ChileanPhone;
use Illuminate\Database\QueryException;
use InvalidArgumentException; // IMPORTANTE: Se añade para manejar el error del RUT

class SocioController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Socio::query();

        if ($searchTerm) {
            // --- INICIO DE LA CORRECCIÓN DEFINITIVA ---
            // Se agrupan las condiciones de búsqueda para un comportamiento predecible
            $query->where(function ($q) use ($searchTerm) {
                // 1. La consulta siempre buscará por el campo 'nombre'.
                $q->where('nombre', 'like', '%' . $searchTerm . '%');

                // 2. Se intenta procesar el término de búsqueda como un RUT.
                try {
                    // Si el texto tiene formato de RUT, se normaliza (ej: 12.345.678-9 -> 123456789)
                    $normalizedRut = Rut::parse($searchTerm)->normalize();
                    // Y se añade una condición para buscar también por el RUT normalizado.
                    $q->orWhere('rut', '=', $normalizedRut);
                } catch (InvalidArgumentException $e) {
                    // 3. Si Rut::parse() falla (porque el texto es un nombre),
                    // la excepción es capturada y no se hace nada. La búsqueda
                    // continúa de forma segura solo por el nombre.
                }
            });
            // --- FIN DE LA CORRECCIÓN ---
        }

        // Se mantiene la paginación y se añade withQueryString() para que el
        // filtro de búsqueda se mantenga al cambiar de página.
        $socios = $query->orderBy('nombre')->paginate(10)->withQueryString();
        
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
    
    public function destroy(Socio $socio)
    {
        try {
            $socio->delete();
            return redirect()->route('socios.index')
                             ->with('success', 'Socio eliminado exitosamente.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->back()
                                 ->with('error', 'No se puede eliminar este socio porque tiene registros asociados (como subsidios o transacciones). Por favor, elimine primero esos registros.');
            }
            return redirect()->back()
                             ->with('error', 'Ocurrió un error en la base de datos al intentar eliminar al socio.');
        }
    }
}