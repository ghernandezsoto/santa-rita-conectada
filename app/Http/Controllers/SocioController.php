<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use Freshwork\ChileanBundle\Rut;
use App\Rules\ChileanPhone;
use Illuminate\Database\QueryException;
use InvalidArgumentException;

use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SocioController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Socio::query();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nombre', 'like', '%' . $searchTerm . '%');

                try {
                    $normalizedRut = Rut::parse($searchTerm)->normalize();
                    $q->orWhere('rut', '=', $normalizedRut);
                } catch (InvalidArgumentException $e) {
                    // La búsqueda continúa de forma segura solo por el nombre.
                }
            });
        }

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
            'email' => 'nullable|email|unique:socios,email|unique:users,email',
            'profesion' => 'nullable|string|max:255',
            'edad' => 'nullable|integer|min:0',
            'estado_civil' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $socio = Socio::create($validated);

        // Se prepara un mensaje de éxito por defecto.
        $successMessage = '¡Socio agregado exitosamente!';

        if ($validated['email']) {
            $temporaryPassword = Str::random(10);

            $user = User::create([
                'name' => $validated['nombre'],
                'email' => $validated['email'],
                'password' => Hash::make($temporaryPassword)
            ]);

            $user->assignRole('Socio');

            session()->flash('password_info', $temporaryPassword);
            $successMessage = '¡Socio y usuario creados! Entregue la contraseña temporal al socio de forma segura.';
        }

        return redirect()->route('socios.index')
                         ->with('success', $successMessage);

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