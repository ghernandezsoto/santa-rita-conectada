<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- IMPORTANTE: Añadir Storage

class TransaccionController extends Controller
{
    public function index()
    {
        $transacciones = Transaccion::with('user')->orderBy('fecha', 'desc')->paginate(15);
        $ingresos = Transaccion::where('tipo', 'Ingreso')->sum('monto');
        $egresos = Transaccion::where('tipo', 'Egreso')->sum('monto');
        $balance = $ingresos - $egresos;
        return view('transacciones.index', compact('transacciones', 'ingresos', 'egresos', 'balance'));
    }

    public function create(Request $request)
    {
        $tipo = $request->query('tipo');
        if (!in_array($tipo, ['Ingreso', 'Egreso'])) {
            return redirect()->route('transacciones.index')->with('error', 'Tipo de transacción no válido.');
        }
        return view('transacciones.create', compact('tipo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:Ingreso,Egreso',
            'monto' => 'required|numeric|min:0',
            'descripcion' => 'required|string|max:255',
            'comprobante' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('comprobante')) {
            $filePath = $request->file('comprobante')->store('comprobantes', 'public');
        }

        Transaccion::create([
            'fecha' => $request->fecha,
            'tipo' => $request->tipo,
            'monto' => $request->monto,
            'descripcion' => $request->descripcion,
            'comprobante_path' => $filePath,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('transacciones.index')
                         ->with('success', '¡Transacción registrada exitosamente!');
    }

    public function show(Transaccion $transaccion)
    {
        //
    }

    public function edit(Transaccion $transaccion)
    {
        $tipo = $transaccion->tipo;
        return view('transacciones.edit', compact('transaccion', 'tipo'));
    }

    public function update(Request $request, Transaccion $transaccion)
    {
        $request->validate([
            'fecha' => 'required|date',
            'monto' => 'required|numeric|min:0',
            'descripcion' => 'required|string|max:255',
            'comprobante' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['fecha', 'monto', 'descripcion']);

        if ($request->hasFile('comprobante')) {
            if ($transaccion->comprobante_path) {
                Storage::disk('public')->delete($transaccion->comprobante_path);
            }
            $data['comprobante_path'] = $request->file('comprobante')->store('comprobantes', 'public');
        }

        $transaccion->update($data);

        return redirect()->route('transacciones.index')
                         ->with('success', '¡Transacción actualizada exitosamente!');
    }

    public function destroy(Transaccion $transaccion)
    {
        if ($transaccion->comprobante_path) {
            Storage::disk('public')->delete($transaccion->comprobante_path);
        }
        $transaccion->delete();

        return redirect()->route('transacciones.index')
                         ->with('success', 'Transacción eliminada exitosamente.');
    }
}