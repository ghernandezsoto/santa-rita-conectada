{{-- resources/views/eventos/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Eventos') }}
            </h2>
            <a href="{{ route('eventos.create') }}"
               class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">
                Agregar Nuevo Evento
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-4"
                             role="status" aria-live="polite">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Aviso de copiado (mejor ubicación en móvil y con aria-live) --}}
                    <div id="copy-notification"
                         class="hidden fixed bottom-6 right-5 z-50 bg-emerald-600 text-white py-2 px-4 rounded-lg shadow-lg"
                         role="status" aria-live="polite">
                        ¡Texto copiado al portapapeles!
                    </div>

                    {{-- VISTA DE ESCRITORIO (TABLA) --}}
                    <div class="hidden md:block">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white table-fixed" role="table" aria-label="Tabla de eventos">
                                <thead class="bg-emerald-800 text-white">
                                    <tr>
                                        <th scope="col" class="w-[40%] py-3 px-4 font-semibold text-sm text-left">Título del Evento</th>
                                        <th scope="col" class="w-[26%] py-3 px-4 font-semibold text-sm text-left">Fecha y Hora</th>
                                        <th scope="col" class="w-[20%] py-3 px-4 font-semibold text-sm text-left">Lugar</th>
                                        <th scope="col" class="w-[14%] py-3 px-4 font-semibold text-sm text-left">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    @forelse ($eventos as $evento)
                                        <tr class="border-b hover:bg-slate-50">
                                            {{-- Título: truncado dentro de celda con ancho fijo --}}
                                            <td class="py-3 px-4 max-w-0">
                                                <div class="font-medium text-emerald-800 truncate"
                                                     title="{{ $evento->titulo }}">
                                                    {{ $evento->titulo }}
                                                </div>
                                            </td>

                                            {{-- Fecha: evita saltos raros, formato compacto --}}
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d/m/Y H:i') }} hrs
                                            </td>

                                            {{-- Lugar: truncado para no desbordar --}}
                                            <td class="py-3 px-4 max-w-0">
                                                <div class="truncate"
                                                     title="{{ $evento->lugar ?? 'No especificado' }}">
                                                    {{ $evento->lugar ?? 'No especificado' }}
                                                </div>
                                            </td>

                                            {{-- Acciones: sin saltos y con botón de copia accesible --}}
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-3 whitespace-nowrap">
                                                    <button class="copy-btn text-blue-600 hover:text-blue-800"
                                                            title="Copiar para WhatsApp"
                                                            aria-label="Copiar mensaje del evento para WhatsApp"
                                                            data-titulo="{{ $evento->titulo }}"
                                                            data-fecha="{{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('l, d \d\e F \d\e Y, H:i') }} hrs"
                                                            data-lugar="{{ $evento->lugar ?? 'No especificado' }}"
                                                            data-descripcion="{{ $evento->descripcion }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                                                        </svg>
                                                        <span class="sr-only">Copiar texto formateado</span>
                                                    </button>

                                                    <a href="{{ route('eventos.edit', $evento) }}"
                                                       class="text-amber-600 hover:text-amber-700 font-medium">
                                                        Editar
                                                    </a>

                                                    <form action="{{ route('eventos.destroy', $evento) }}"
                                                          method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="text-red-600 hover:text-red-700 font-medium"
                                                                onclick="return confirm('¿Estás seguro?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-6 text-center text-gray-500">
                                                No hay eventos registrados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- VISTA MÓVIL (TARJETAS) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($eventos as $evento)
                            <div class="bg-white p-4 rounded-lg shadow border border-gray-200 space-y-3">
                                <h3 class="font-bold text-emerald-800 truncate"
                                    title="{{ $evento->titulo }}">
                                    {{ $evento->titulo }}
                                </h3>

                                <div class="text-sm text-gray-600">
                                    <p>
                                        <span class="font-semibold">Fecha:</span>
                                        {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d/m/Y H:i') }} hrs
                                    </p>
                                    <p class="break-words">
                                        <span class="font-semibold">Lugar:</span>
                                        {{ $evento->lugar ?? 'No especificado' }}
                                    </p>
                                </div>

                                <div class="mt-4 flex items-center gap-4 border-t pt-3">
                                    <button class="copy-btn text-blue-600 hover:text-blue-800 flex items-center gap-1 text-sm"
                                            title="Copiar para WhatsApp"
                                            aria-label="Copiar mensaje del evento para WhatsApp"
                                            data-titulo="{{ $evento->titulo }}"
                                            data-fecha="{{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('l, d \d\e F \d\e Y, H:i') }} hrs"
                                            data-lugar="{{ $evento->lugar ?? 'No especificado' }}"
                                            data-descripcion="{{ $evento->descripcion }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                             viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                                        </svg>
                                        Copiar
                                    </button>

                                    <a href="{{ route('eventos.edit', $evento) }}"
                                       class="text-amber-600 hover:text-amber-700 font-medium text-sm">
                                        Editar
                                    </a>

                                    <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-700 font-medium text-sm"
                                                onclick="return confirm('¿Estás seguro?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-gray-500">
                                No hay eventos registrados.
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
                // HTTPS y navegadores modernos
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(text);
                }
                // Fallback para HTTP / navegadores antiguos
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
