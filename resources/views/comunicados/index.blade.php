<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Comunicados') }}
            </h2>
            <a href="{{ route('comunicados.create') }}"
               class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">
                Nuevo Comunicado
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Mensajes de éxito o error --}}
                    @if (session('success'))
                        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Notificación para copia --}}
                    <div id="copy-notification" class="hidden fixed top-20 right-5 bg-emerald-600 text-white py-2 px-4 rounded-lg shadow-lg z-50">
                        ¡Texto copiado al portapapeles!
                    </div>

                    <div class="hidden md:block">
                        <table class="min-w-full bg-white">
                            <thead class="bg-emerald-800 text-white">
                                <tr>
                                    <th class="w-1/2 py-3 px-4 font-semibold text-sm text-left">Título</th>
                                    <th class="w-1/4 py-3 px-4 font-semibold text-sm text-left">Estado</th>
                                    <th class="w-1/4 py-3 px-4 font-semibold text-sm text-left">Enviado por</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($comunicados as $comunicado)
                                    <tr class="border-b hover:bg-slate-50">
                                        <td class="py-3 px-4">
                                            <div class="truncate max-w-md" title="{{ $comunicado->titulo }}">{{ $comunicado->titulo }}</div>
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            @if ($comunicado->fecha_envio)
                                                <span class="bg-emerald-200 text-emerald-700 py-1 px-3 rounded-full text-xs">Enviado el {{ \Carbon\Carbon::parse($comunicado->fecha_envio)->format('d/m/Y') }}</span>
                                            @else
                                                <span class="bg-amber-100 text-amber-700 py-1 px-3 rounded-full text-xs">Borrador</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 truncate" title="{{ $comunicado->user->name }}">{{ $comunicado->user->name }}</td>
                                        
                                        <td class="py-3 px-4">
                                            {{-- ===== INICIO DE LA IMPLEMENTACIÓN CON FLOATING UI ===== --}}
                                            <div x-data="dropdown()" @click.away="close()" class="relative inline-block text-left w-full text-center">
                                                <button @click="toggle()" x-ref="button" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                                    Acciones
                                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>

                                                <div x-show="open" x-ref="panel" x-transition style="display: none;" class="absolute w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                                                    <div class="py-1" role="none">
                                                        <a href="{{ route('comunicados.show', $comunicado->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 text-left">Ver</a>
                                                        @if ($comunicado->fecha_envio)
                                                            <button type="button" class="copy-btn text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100" data-titulo="{{ $comunicado->titulo }}" data-contenido="{{ $comunicado->contenido }}">Copiar para WhatsApp</button>
                                                        @endif
                                                        @if (!$comunicado->fecha_envio)
                                                            <a href="{{ route('comunicados.edit', $comunicado->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 text-left">Editar</a>
                                                            <form action="{{ route('comunicados.enviar', $comunicado->id) }}" method="POST" onsubmit="return confirm('¿Enviar este comunicado a todos los socios activos?')">
                                                                @csrf
                                                                <button type="submit" class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Enviar</button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('comunicados.destroy', $comunicado->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este comunicado?')">
                                                            @csrf 
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Eliminar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- ===== FIN DE LA IMPLEMENTACIÓN ===== --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-6 text-center text-gray-500">No hay comunicados registrados aún.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Vista en tarjetas (solo móviles) --}}
                    <div class="space-y-4 md:hidden">
                       {{-- ... El contenido de la vista móvil no necesita cambios ... --}}
                       @forelse ($comunicados as $comunicado)
                            <div class="bg-white border rounded-lg shadow-sm p-4">
                                <h3 class="font-semibold text-lg truncate" title="{{ $comunicado->titulo }}">{{ $comunicado->titulo }}</h3>
                                <p class="text-sm mt-1"><span class="font-medium">Estado:</span>
                                    @if ($comunicado->fecha_envio)
                                        <span class="bg-emerald-200 text-emerald-700 py-1 px-2 rounded-full text-xs">Enviado el {{ \Carbon\Carbon::parse($comunicado->fecha_envio)->format('d/m/Y') }}</span>
                                    @else
                                        <span class="bg-amber-100 text-amber-700 py-1 px-2 rounded-full text-xs">Borrador</span>
                                    @endif
                                </p>
                                <p class="text-sm mt-1"><span class="font-medium">Por:</span> {{ $comunicado->user->name }}</p>
                                <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 border-t pt-3">
                                    @if ($comunicado->fecha_envio)
                                        <button type="button" class="copy-btn text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center gap-1" title="Copiar para WhatsApp" data-titulo="{{ $comunicado->titulo }}" data-contenido="{{ $comunicado->contenido }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" /></svg>
                                            Copiar
                                        </button>
                                    @endif
                                    <a href="{{ route('comunicados.show', $comunicado->id) }}" class="text-emerald-600 hover:text-emerald-900 font-medium text-sm">Ver</a>
                                    @if (!$comunicado->fecha_envio)
                                        <a href="{{ route('comunicados.edit', $comunicado->id) }}" class="text-amber-600 hover:text-amber-700 font-medium text-sm">Editar</a>
                                        <form action="{{ route('comunicados.enviar', $comunicado->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-amber-600 hover:text-amber-700 font-medium text-sm" onclick="return confirm('¿Enviar este comunicado a todos los socios activos?')">Enviar</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('comunicados.destroy', $comunicado->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm" onclick="return confirm('¿Eliminar este comunicado?')">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-6">No hay comunicados registrados aún.</p>
                        @endforelse
                    </div>

                    <div class="mt-4">{{ $comunicados->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT DE LA VISTA (SIN CAMBIOS) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ... (tu script para copiar a whatsapp sigue aquí, no se toca)
        });
    </script>

    {{-- SCRIPT PARA EL DROPDOWN (NUEVO) --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dropdown', function () {
                return {
                    open: false,
                    // Función para calcular y actualizar la posición del panel
                    updatePosition() {
                        const { computePosition, flip, shift, offset } = window.FloatingUIDOM;
                        
                        computePosition(this.$refs.button, this.$refs.panel, {
                            placement: 'bottom-end',
                            middleware: [
                                offset(8), // Distancia entre el botón y el panel
                                flip(),    // Cambia a 'top-end' si no hay espacio abajo
                                shift({ padding: 5 }) // Asegura que se mantenga dentro de la pantalla
                            ]
                        }).then(({ x, y }) => {
                            Object.assign(this.$refs.panel.style, {
                                left: `${x}px`,
                                top: `${y}px`,
                            });
                        });
                    },
                    // Muestra el panel y actualiza su posición
                    toggle() {
                        if (this.open) {
                            return this.close();
                        }
                        this.open = true;
                        // nextTick asegura que el panel sea visible antes de calcular su posición
                        this.$nextTick(() => this.updatePosition());
                    },
                    // Oculta el panel
                    close() {
                        this.open = false;
                    }
                }
            });
        });
    </script>
</x-app-layout>