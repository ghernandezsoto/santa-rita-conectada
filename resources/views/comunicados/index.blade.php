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
            {{-- ===== CAMBIO ÚNICO AQUÍ: Se eliminó la clase "overflow-hidden" ===== --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
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
                                            {{-- Tu implementación de Alpine/Floating UI es correcta y se mantiene --}}
                                            <div x-data="dropdown()" @click.away="close()" class="relative inline-block text-left w-full text-center">
                                                <button @click="toggle()" x-ref="button" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                                    Acciones
                                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>

                                                <div x-show="open" x-ref="panel" x-transition style="display: none;" class="absolute w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-20">
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
                        @forelse ($comunicados as $comunicado)
                            {{-- ... No se necesitan cambios aquí ... --}}
                        @empty
                            <p class="text-center text-gray-500 py-6">No hay comunicados registrados aún.</p>
                        @endforelse
                    </div>

                    <div class="mt-4">{{ $comunicados->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tu script para copiar a whatsapp se mantiene --}}
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

    {{-- Tu script para el dropdown es perfecto y se mantiene --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dropdown', function () {
                return {
                    open: false,
                    updatePosition() {
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