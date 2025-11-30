<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Comunicados') }}
            </h2>
            
            <a href="{{ route('comunicados.create') }}"
               class="px-4 py-2 bg-accent-500 text-white rounded-lg hover:bg-accent-600 flex items-center gap-2 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nuevo Comunicado
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Alertas --}}
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r" role="alert">
                            <strong class="font-bold">¡Éxito!</strong> {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r" role="alert">
                            <strong class="font-bold">¡Error!</strong> {{ session('error') }}
                        </div>
                    @endif

                    <div id="copy-notification" class="hidden fixed top-20 right-5 bg-green-600 text-white py-2 px-4 rounded-lg shadow-lg z-50 transition-opacity duration-300">
                        ¡Texto copiado al portapapeles!
                    </div>

                    {{-- VISTA ESCRITORIO (TABLA) --}}
                    <div class="hidden md:block">
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full bg-white">
                                <thead class="bg-primary-800 text-white uppercase text-xs leading-normal">
                                    <tr>
                                        <th class="w-2/5 py-3 px-4 font-semibold text-left">Título</th>
                                        <th class="w-1/5 py-3 px-4 font-semibold text-left">Estado</th>
                                        <th class="w-1/5 py-3 px-4 font-semibold text-left">Autor</th>
                                        <th class="w-1/5 py-3 px-4 font-semibold text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                    @forelse ($comunicados as $comunicado)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                            <td class="py-3 px-4 font-medium text-gray-800">
                                                <div class="truncate max-w-xs" title="{{ $comunicado->titulo }}">{{ $comunicado->titulo }}</div>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                @if ($comunicado->fecha_envio)
                                                    <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-xs font-semibold">
                                                        Enviado {{ \Carbon\Carbon::parse($comunicado->fecha_envio)->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="bg-gray-100 text-gray-600 py-1 px-3 rounded-full text-xs font-semibold">Borrador</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 truncate">{{ $comunicado->user->name }}</td>
                                            
                                            {{-- COLUMNA DE ACCIONES COMPLETA --}}
                                            <td class="py-3 px-4 text-center">
                                                <div class="flex items-center justify-center gap-3">
                                                    {{-- Ver --}}
                                                    <a href="{{ route('comunicados.show', $comunicado->id) }}" class="text-blue-500 hover:text-blue-700 hover:scale-110 transform transition" title="Ver detalle">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                    </a>

                                                    @if ($comunicado->fecha_envio)
                                                        {{-- Copiar WhatsApp --}}
                                                        <button type="button" class="copy-btn text-green-500 hover:text-green-700 hover:scale-110 transform transition" title="Copiar para WhatsApp" data-titulo="{{ $comunicado->titulo }}" data-contenido="{{ $comunicado->contenido }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" /></svg>
                                                        </button>
                                                    @else
                                                        {{-- Editar --}}
                                                        <a href="{{ route('comunicados.edit', $comunicado->id) }}" class="text-yellow-500 hover:text-yellow-700 hover:scale-110 transform transition" title="Editar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                        </a>
                                                        {{-- Enviar --}}
                                                        <form action="{{ route('comunicados.enviar', $comunicado->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Enviar este comunicado a todos los socios activos?')">
                                                            @csrf
                                                            <button type="submit" class="text-accent-500 hover:text-accent-700 hover:scale-110 transform transition" title="Enviar ahora">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Eliminar --}}
                                                    <form action="{{ route('comunicados.destroy', $comunicado->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este comunicado?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700 hover:scale-110 transform transition" title="Eliminar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="py-8 text-center text-gray-500">No hay registros.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- VISTA MÓVIL (TARJETAS) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($comunicados as $comunicado)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                                <h3 class="font-bold text-lg text-primary-800 break-words">{{ $comunicado->titulo }}</h3>
                                
                                <div class="mt-2 flex flex-wrap gap-2 text-sm">
                                    @if ($comunicado->fecha_envio)
                                        <span class="bg-green-100 text-green-700 py-1 px-2 rounded-full font-semibold">Enviado {{ \Carbon\Carbon::parse($comunicado->fecha_envio)->format('d/m/Y') }}</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-600 py-1 px-2 rounded-full font-semibold">Borrador</span>
                                    @endif
                                </div>
                                <p class="text-sm mt-1 text-gray-500">Autor: {{ $comunicado->user->name }}</p>

                                {{-- ACCIONES MÓVIL --}}
                                <div class="mt-4 flex flex-wrap gap-4 border-t pt-3 justify-end items-center">
                                    <a href="{{ route('comunicados.show', $comunicado->id) }}" class="text-blue-600 font-medium text-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg> Ver
                                    </a>

                                    @if ($comunicado->fecha_envio)
                                        <button type="button" class="text-green-600 font-medium text-sm flex items-center gap-1 copy-btn" data-titulo="{{ $comunicado->titulo }}" data-contenido="{{ $comunicado->contenido }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" /></svg> Copiar
                                        </button>
                                    @else
                                        <a href="{{ route('comunicados.edit', $comunicado->id) }}" class="text-yellow-600 font-medium text-sm flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg> Editar
                                        </a>
                                        <form action="{{ route('comunicados.enviar', $comunicado->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Enviar?')">
                                            @csrf
                                            <button type="submit" class="text-accent-600 font-medium text-sm flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg> Enviar
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('comunicados.destroy', $comunicado->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 font-medium text-sm flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500">No hay comunicados.</div>
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
                    }).catch(err => console.error('Error:', err));
                });
            });
        });
    </script>
</x-app-layout>
