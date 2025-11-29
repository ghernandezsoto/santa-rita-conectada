<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Comunicados') }}
            </h2>
            
            {{-- Botón con color accent-500 e Icono añadido para consistencia --}}
            <a href="{{ route('comunicados.create') }}"
               class="px-4 py-2 bg-accent-500 text-white rounded-lg hover:bg-accent-600 flex items-center gap-2 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nuevo Comunicado
            </a>
        </div>
    </x-slot>

    {{-- Fondo base-100 --}}
    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Mensajes de éxito o error (Estandarizados a Green/Red para consistencia con Eventos) --}}
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r" role="alert">
                            <strong class="font-bold">¡Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div id="copy-notification" class="hidden fixed top-20 right-5 bg-green-600 text-white py-2 px-4 rounded-lg shadow-lg z-50 transition-opacity duration-300">
                        ¡Texto copiado al portapapeles!
                    </div>

                    {{-- VISTA DE ESCRITORIO (TABLA) --}}
                    <div class="hidden md:block overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full bg-white">
                            {{-- Header primary-800 con estilos de fuente consistentes --}}
                            <thead class="bg-primary-800 text-white uppercase text-xs leading-normal">
                                <tr>
                                    <th class="w-1/2 py-3 px-4 font-semibold text-left">Título</th>
                                    <th class="w-1/4 py-3 px-4 font-semibold text-left">Estado</th>
                                    <th class="w-1/4 py-3 px-4 font-semibold text-left">Enviado por</th>
                                    <th class="py-3 px-4 font-semibold text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @forelse ($comunicados as $comunicado)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-gray-800">
                                            <div class="truncate max-w-md" title="{{ $comunicado->titulo }}">{{ $comunicado->titulo }}</div>
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            @if ($comunicado->fecha_envio)
                                                <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-xs font-semibold">
                                                    Enviado el {{ \Carbon\Carbon::parse($comunicado->fecha_envio)->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="bg-gray-100 text-gray-600 py-1 px-3 rounded-full text-xs font-semibold">
                                                    Borrador
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 truncate" title="{{ $comunicado->user->name }}">
                                            {{ $comunicado->user->name }}
                                        </td>
                                        
                                        <td class="py-3 px-4 text-center">
                                            {{-- Lógica del Dropdown intacta --}}
                                            <div x-data="dropdown()" @click.away="close()" class="relative inline-block text-left w-full text-center">
                                                <button @click="toggle()" x-ref="button" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition">
                                                    Acciones
                                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>

                                                <div x-show="open" x-ref="panel" x-transition style="display: none;" class="absolute w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                                    <div class="py-1" role="none">
                                                        <a href="{{ route('comunicados.show', $comunicado->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 text-left">
                                                            Ver
                                                        </a>
                                                        @if ($comunicado->fecha_envio)
                                                            <button type="button" class="copy-btn text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100" data-titulo="{{ $comunicado->titulo }}" data-contenido="{{ $comunicado->contenido }}">
                                                                Copiar para WhatsApp
                                                            </button>
                                                        @endif
                                                        @if (!$comunicado->fecha_envio)
                                                            <a href="{{ route('comunicados.edit', $comunicado->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 text-left">
                                                                Editar
                                                            </a>
                                                            <form action="{{ route('comunicados.enviar', $comunicado->id) }}" method="POST" onsubmit="return confirm('¿Enviar este comunicado a todos los socios activos?')">
                                                                @csrf
                                                                <button type="submit" class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                                                                    Enviar
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('comunicados.destroy', $comunicado->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este comunicado?')">
                                                            @csrf 
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                                                                Eliminar
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500 bg-gray-50">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                                                <p>No hay comunicados registrados aún.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Vista en tarjetas (solo móviles) --}}
                    <div class="space-y-4 md:hidden">
                        @forelse ($comunicados as $comunicado)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                                {{-- Título primary-800 --}}
                                <h3 class="font-bold text-lg text-primary-800 truncate" title="{{ $comunicado->titulo }}">{{ $comunicado->titulo }}</h3>
                                
                                <p class="text-sm mt-2 flex items-center gap-2">
                                    <span class="font-medium text-gray-600">Estado:</span>
                                    @if ($comunicado->fecha_envio)
                                        <span class="bg-green-100 text-green-700 py-1 px-2 rounded-full text-xs font-semibold">Enviado el {{ \Carbon\Carbon::parse($comunicado->fecha_envio)->format('d/m/Y') }}</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-600 py-1 px-2 rounded-full text-xs font-semibold">Borrador</span>
                                    @endif
                                </p>
                                
                                <p class="text-sm mt-1 text-gray-500"><span class="font-medium">Por:</span> {{ $comunicado->user->name }}</p>

                                <div class="mt-3 flex flex-wrap items-center gap-4 border-t pt-3 justify-end">
                                    @if ($comunicado->fecha_envio)
                                        <button type="button" class="copy-btn text-green-600 hover:text-green-800 text-sm font-medium flex items-center gap-1" title="Copiar para WhatsApp" data-titulo="{{ $comunicado->titulo }}" data-contenido="{{ $comunicado->contenido }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" /></svg>
                                            Copiar
                                        </button>
                                    @endif

                                    <a href="{{ route('comunicados.show', $comunicado->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        Ver
                                    </a>

                                    @if (!$comunicado->fecha_envio)
                                        <a href="{{ route('comunicados.edit', $comunicado->id) }}" class="text-yellow-600 hover:text-yellow-700 font-medium text-sm flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            Editar
                                        </a>
                                        <form action="{{ route('comunicados.enviar', $comunicado->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-accent-600 hover:text-accent-700 font-medium text-sm flex items-center gap-1" onclick="return confirm('¿Enviar este comunicado a todos los socios activos?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                                Enviar
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('comunicados.destroy', $comunicado->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm flex items-center gap-1" onclick="return confirm('¿Eliminar este comunicado?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                No hay comunicados registrados aún.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">{{ $comunicados->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const copyButtons = document.querySelectorAll('.copy-btn');
            const notification = document.getElementById('copy-notification');

            copyButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const titulo = this.dataset.titulo;
                    const contenido = this.dataset.contenido;
                    const whatsappText = `*¡Nuevo Comunicado de la Junta de Vecinos!* 📢\n\n*Asunto:* ${titulo}\n\n${contenido}`;

                    navigator.clipboard.writeText(whatsappText).then(() => {
                        notification.classList.remove('hidden');
                        setTimeout(() => { notification.classList.add('hidden'); }, 2000);
                    }).catch(err => console.error('Error al copiar texto: ', err));
                });
            });
        });
    </script>

    {{-- Script Alpine se mantiene intacto --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dropdown', function () {
                return {
                    open: false,
                    updatePosition() {
                        if (!window.FloatingUIDOM) return; // Prevención simple
                        const { computePosition, flip, shift, offset } = window.FloatingUIDOM;
                        
                        computePosition(this.$refs.button, this.$refs.panel, {
                            placement: 'bottom-end',
                            middleware: [
                                offset(8),
                                flip(),
                                shift({ padding: 5 })
                            ]
                        }).then(({ x, y }) => {
                            Object.assign(this.$refs.panel.style, {
                                left: `${x}px`,
                                top: `${y}px`,
                            });
                        });
                    },
                    toggle() {
                        if (this.open) {
                            return this.close();
                        }
                        this.open = true;
                        this.$nextTick(() => this.updatePosition());
                    },
                    close() {
                        this.open = false;
                    }
                }
            });
        });
    </script>
</x-app-layout>