<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use Illuminate\Http\Request;

class TransaccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las transacciones ordenadas por fecha reciente
        $transacciones = Transaccion::with('user')->orderBy('fecha', 'desc')->paginate(15);

        // Calcular totales
        $ingresos = Transaccion::where('tipo', 'Ingreso')->sum('monto');
        $egresos = Transaccion::where('tipo', 'Egreso')->sum('monto');
        $balance = $ingresos - $egresos;

        // Pasar todas las variables a la vista
        return view('transacciones.index', compact('transacciones', 'ingresos', 'egresos', 'balance'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Pasamos el 'tipo' de transacción a la vista para saber qué formulario mostrar.
        $tipo = $request->query('tipo');

        // Validamos que el tipo sea 'Ingreso' o 'Egreso'.
        if (!in_array($tipo, ['Ingreso', 'Egreso'])) {
            return redirect()->route('transacciones.index')->with('error', 'Tipo de transacción no válido.');
        }

        return view('transacciones.create', compact('tipo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:Ingreso,Egreso',
            'monto' => 'required|numeric|min:0',
            'descripcion' => 'required|string|max:255',
        ]);

        Transaccion::create([
            'fecha' => $request->fecha,
            'tipo' => $request->tipo,
            'monto' => $request->monto,
            'descripcion' => $request->descripcion,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('transacciones.index')
                        ->with('success', '¡Transacción registrada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaccion $transaccion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaccion $transaccion)
    {
        // Pasamos la transacción y el tipo a la vista de edición.
        $tipo = $transaccion->tipo;
        return view('transacciones.edit', compact('transaccion', 'tipo'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaccion $transaccion)
    {
        $request->validate([
            'fecha' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'descripcion' => 'required|string|max:255',
        ]);

        $transaccion->update($request->only(['fecha', 'monto', 'descripcion']));

        return redirect()->route('transacciones.index')
                        ->with('success', '¡Transacción actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaccion $transaccion)
    {
        $transaccion->delete();

        return redirect()->route('transacciones.index')
                        ->with('success', 'Transacción eliminada exitosamente.');
    }
}
