<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\User;
use App\Notifications\NuevoComunicadoNotification;
use App\Notifications\PushComunicadoNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Comunicado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\ComunicadoService;

class ComunicadoController extends Controller
{

    public function index()
    {
        $comunicados = Comunicado::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('comunicados.index', compact('comunicados'));
    }


    public function create()
    {
        return view('comunicados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        Comunicado::create([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('comunicados.index')
                         ->with('success', '¡Comunicado creado exitosamente!');
    }


    public function show(Comunicado $comunicado)
    {
        return view('comunicados.show', compact('comunicado'));
    }


    public function edit(Comunicado $comunicado)
    {
        return view('comunicados.edit', compact('comunicado'));
    }

    public function update(Request $request, Comunicado $comunicado)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        $comunicado->update($request->all());

        return redirect()->route('comunicados.index')
                         ->with('success', '¡Comunicado actualizado exitosamente!');
    }


    public function destroy(Comunicado $comunicado)
    {
        $comunicado->delete();
        return redirect()->route('comunicados.index')
                         ->with('success', 'Comunicado eliminado exitosamente.');
    }

    // --- Se inyecta ComunicadoService y se usa ---
    public function enviar(Comunicado $comunicado, ComunicadoService $comunicadoService)
    {
        if ($comunicado->fecha_envio) {
            return redirect()->route('comunicados.index')->with('error', 'Este comunicado ya fue enviado.');
        }

        // Se reemplaza toda la lógica de envío por el servicio.
        // El servicio ya se encarga de marcar la 'fecha_envio'.
        $comunicadoService->enviar($comunicado);

        return redirect()->route('comunicados.index')
                         ->with('success', '¡El comunicado se ha puesto en la cola para ser enviado!');
    }
}