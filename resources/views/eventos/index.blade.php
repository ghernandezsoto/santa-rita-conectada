<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Eventos') }}
            </h2>
            {{-- Usamos tu color accent-500 original --}}
            <a href="{{ route('eventos.create') }}" class="px-4 py-2 bg-accent-500 text-white rounded-lg hover:bg-accent-600 flex items-center gap-2 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Agregar Nuevo Evento
            </a>
        </div>
    </x-slot>

    {{-- Fondo base-100 original --}}
    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r" role="alert">
                            <p class="font-bold">¡Éxito!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div id="copy-notification" class="hidden fixed bottom-6 right-5 z-50 bg-green-600 text-white py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300" role="alert">
                        ¡Texto copiado para WhatsApp!
                    </div>

                    {{-- VISTA DE ESCRITORIO (TABLA) --}}
                    <div class="hidden md:block overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full bg-white">
                            {{-- Header primary-800 original --}}
                            <thead class="bg-primary-800 text-white uppercase text-xs leading-normal">
                                <tr>
                                    <th class="py-3 px-4 text-left font-semibold">Título del Evento</th>
                                    <th class="py-3 px-4 text-center font-semibold">Fecha y Hora</th>
                                    <th class="py-3 px-4 text-left font-semibold">Lugar</th>
                                    <th class="py-3 px-4 text-center font-semibold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @forelse ($eventos as $evento)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-gray-800">
                                            {{ $evento->titulo }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="bg-blue-50 text-blue-700 py-1 px-3 rounded-full text-xs font-semibold">
                                                {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d/m/Y H:i') }} hrs
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 truncate max-w-xs" title="{{ $evento->lugar ?? 'No especificado' }}">
                                            {{ $evento->lugar ?? 'No especificado' }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex item-center justify-center gap-3">
                                                {{-- Botón WhatsApp (CORREGIDO EL CIERRE DE ETIQUETA) --}}
                                                <button class="copy-btn text-green-500 hover:text-green-700 transform hover:scale-110 transition"
                                                        title="Copiar para WhatsApp"
                                                        data-titulo="{{ $evento->titulo }}"
                                                        data-fecha="{{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('l, d \d\e F \d\e Y, H:i') }} hrs"
                                                        data-lugar="{{ $evento->lugar ?? 'No especificado' }}"
                                                        data-descripcion="{{ $evento->descripcion }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                                    </svg>
                                                </button> {{-- CIERRE CORREGIDO --}}
                                    
                                                {{-- Botón Editar (Usando Model Binding) --}}
                                                <a href="{{ route('eventos.edit', $evento) }}" title="Editar" class="text-yellow-500 hover:text-yellow-700 transform hover:scale-110 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                                
                                                {{-- Botón Eliminar (Usando Model Binding) --}}
                                                <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este evento?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Eliminar" class="text-red-500 hover:text-red-700 transform hover:scale-110 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500 bg-gray-50">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                <p>No hay eventos registrados.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- VISTA MÓVIL (TARJETAS) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($eventos as $evento)
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 space-y-3">
                                {{-- Título con color primary-800 original --}}
                                <h3 class="font-bold text-primary-800 text-lg mb-1">{{ $evento->titulo }}</h3>
                                
                                <div class="text-sm text-gray-600 space-y-1 mb-3">
                                    <p class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <span class="font-semibold">{{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d/m/Y H:i') }} hrs</span>
                                    </p>
                                    <p class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        <span class="break-words">{{ $evento->lugar ?? 'No especificado' }}</span>
                                    </p>
                                </div>

                                <div class="flex justify-end items-center gap-4 border-t pt-3">
                                    <button class="copy-btn text-green-600 font-medium text-sm flex items-center gap-1"
                                            data-titulo="{{ $evento->titulo }}"
                                            data-fecha="{{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('l, d \d\e F \d\e Y, H:i') }} hrs"
                                            data-lugar="{{ $evento->lugar ?? 'No especificado' }}"
                                            data-descripcion="{{ $evento->descripcion }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                        WhatsApp
                                    </button>
                                    
                                    <a href="{{ route('eventos.edit', $evento) }}" class="text-yellow-600 font-medium text-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        Editar
                                    </a>
                                    
                                    <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 font-medium text-sm flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                No hay eventos.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $eventos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const copyButtons = document.querySelectorAll('.copy-btn');
            const notification = document.getElementById('copy-notification');
            let notifyTimer = null;

            function copyToClipboard(text) {
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(text);
                }
                return new Promise((resolve, reject) => {
                    const ta = document.createElement('textarea');
                    ta.value = text;
                    ta.setAttribute('readonly', '');
                    ta.style.position = 'absolute';
                    ta.style.left = '-9999px';
                    document.body.appendChild(ta);
                    ta.select();
                    ta.setSelectionRange(0, text.length);
                    try {
                        const ok = document.execCommand('copy');
                        document.body.removeChild(ta);
                        ok ? resolve() : reject();
                    } catch (e) {
                        document.body.removeChild(ta);
                        reject(e);
                    }
                });
            }

            copyButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const titulo = this.dataset.titulo || '';
                    const fecha = this.dataset.fecha || '';
                    const lugar = this.dataset.lugar || '';
                    const descripcion = this.dataset.descripcion || '';

                    const whatsappText =
                        `*¡Recordatorio de Evento!* 📢\n\n` +
                        `*Evento:* ${titulo}\n` +
                        `*Fecha:* ${fecha}\n` +
                        `*Lugar:* ${lugar}\n\n` +
                        `*Descripción:*\n${descripcion}\n\n` +
                        `¡No faltes!`;

                    copyToClipboard(whatsappText).then(() => {
                        notification.classList.remove('hidden');
                        if (notifyTimer) clearTimeout(notifyTimer);
                        notifyTimer = setTimeout(() => {
                            notification.classList.add('hidden');
                        }, 2000);
                    }).catch(() => {
                        alert('No se pudo copiar el texto. Intenta manualmente.');
                    });
                });
            });
        });
    </script>
</x-app-layout>