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

class ComunicadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comunicados = Comunicado::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('comunicados.index', compact('comunicados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('comunicados.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Comunicado $comunicado)
    {
        return view('comunicados.show', compact('comunicado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comunicado $comunicado)
    {
        return view('comunicados.edit', compact('comunicado'));
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comunicado $comunicado)
    {
        $comunicado->delete();
        return redirect()->route('comunicados.index')
                         ->with('success', 'Comunicado eliminado exitosamente.');
    }


    public function enviar(Comunicado $comunicado)
    {
        if ($comunicado->fecha_envio) {
            return redirect()->route('comunicados.index')->with('error', 'Este comunicado ya fue enviado.');
        }

        $comunicado->update(['fecha_envio' => now()]);

        // Usamos el Log que ya tenías importado
        Log::info('=========================================');
        Log::info('[CONTROLLER] Iniciando envío para comunicado ID: '. $comunicado->id);

        // 1. Enviar por Email (sin cambios)
        $sociosParaEmail = Socio::where('estado', 'Activo')->whereNotNull('email')->get();
        Log::info('[CONTROLLER] Socios encontrados para email: '. $sociosParaEmail->count());

        if ($sociosParaEmail->isNotEmpty()) {
            Notification::send($sociosParaEmail, new NuevoComunicadoNotification($comunicado));
            Log::info('[CONTROLLER] Tarea de email encolada.');
        }

        // --- INICIO DE LA PRUEBA DEL "CAMBIAZO" ---
        Log::info('[CONTROLLER] PRUEBA FINAL: Intentando encolar PUSH para un modelo SOCIO.');
        
        // Buscamos un único socio activo para usarlo como sujeto de prueba.
        $socioDePrueba = Socio::where('estado', 'Activo')->first();

        if ($socioDePrueba) {
            Log::info('[CONTROLLER] Sujeto de prueba encontrado: SOCIO ID '. $socioDePrueba->id);
            
            // Enviamos la notificación push al SOCIO en lugar del USER.
            Notification::send($socioDePrueba, new PushComunicadoNotification($comunicado));
            
            Log::info('[CONTROLLER] Tarea de push para SOCIO encolada.');
        } else {
            Log::info('[CONTROLLER] No se encontraron socios activos para la prueba del "cambiazo".');
        }
        // --- FIN DE LA PRUEBA DEL "CAMBIAZO" ---

        Log::info('[CONTROLLER] Proceso de envío finalizado en controlador.');
        Log::info('=========================================');

        return redirect()->route('comunicados.index')
                        ->with('success', '¡El comunicado se ha puesto en la cola para ser enviado!');
    }
}