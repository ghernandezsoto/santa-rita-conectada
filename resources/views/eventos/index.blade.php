<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Eventos') }}
            </h2>
            <a href="{{ route('eventos.create') }}"
               class="px-4 py-2 bg-accent-500 text-white rounded-lg hover:bg-accent-600">
                Agregar Nuevo Evento
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-success-100 border border-success-400 text-success-700 px-4 py-3 rounded relative mb-4"
                             role="status" aria-live="polite">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div id="copy-notification"
                         class="hidden fixed bottom-6 right-5 z-50 bg-success-600 text-white py-2 px-4 rounded-lg shadow-lg"
                         role="status" aria-live="polite">
                        ¡Texto copiado al portapapeles!
                    </div>

                    {{-- VISTA DE ESCRITORIO (TABLA) --}}
                    <div class="hidden md:block">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white table-fixed" role="table" aria-label="Tabla de eventos">
                                <thead class="bg-primary-800 text-white">
                                    <tr>
                                        <th scope="col" class="w-[40%] py-3 px-4 font-semibold text-sm text-left">Título del Evento</th>
                                        <th scope="col" class="w-[26%] py-3 px-4 font-semibold text-sm text-left">Fecha y Hora</th>
                                        <th scope="col" class="w-[20%] py-3 px-4 font-semibold text-sm text-left">Lugar</th>
                                        <th scope="col" class="w-[14%] py-3 px-4 font-semibold text-sm text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    @forelse ($eventos as $evento)
                                        <tr class="border-b hover:bg-base-50">
                                            <td class="py-3 px-4 max-w-0">
                                                <div class="font-medium text-primary-800 truncate" title="{{ $evento->titulo }}">
                                                    {{ $evento->titulo }}
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d/m/Y H:i') }} hrs
                                            </td>
                                            <td class="py-3 px-4 max-w-0">
                                                <div class="truncate" title="{{ $evento->lugar ?? 'No especificado' }}">
                                                    {{ $evento->lugar ?? 'No especificado' }}
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center justify-center gap-4">
                                                    <button class="copy-btn text-gray-400 hover:text-primary-600 transition-colors duration-200"
                                                            title="Copiar para WhatsApp"
                                                            aria-label="Copiar mensaje del evento para WhatsApp"
                                                            data-titulo="{{ $evento->titulo }}"
                                                            data-fecha="{{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('l, d \d\e F \d\e Y, H:i') }} hrs"
                                                            data-lugar="{{ $evento->lugar ?? 'No especificado' }}"
                                                            data-descripcion="{{ $evento->descripcion }}">
                                                        <x-icons.share class="w-5 h-5" />
                                                    </button>
                                                    <a href="{{ route('eventos.edit', $evento) }}" title="Editar" class="text-gray-400 hover:text-accent-600 transition-colors duration-200">
                                                        <x-icons.pencil class="w-5 h-5" />
                                                    </a>
                                                    <form action="{{ route('eventos.destroy', $evento) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" title="Eliminar" class="text-gray-400 hover:text-danger-600 transition-colors duration-200">
                                                            <x-icons.trash class="w-5 h-5" />
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-6 text-center text-gray-500">No hay eventos registrados.</td>
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
                                <h3 class="font-bold text-primary-800 truncate" title="{{ $evento->titulo }}">
                                    {{ $evento->titulo }}
                                </h3>
                                <div class="text-sm text-gray-600">
                                    <p><span class="font-semibold">Fecha:</span> {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d/m/Y H:i') }} hrs</p>
                                    <p class="break-words"><span class="font-semibold">Lugar:</span> {{ $evento->lugar ?? 'No especificado' }}</p>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-end gap-6">
                                    <button class="copy-btn text-gray-400 hover:text-primary-600 transition-colors duration-200"
                                            title="Copiar para WhatsApp"
                                            aria-label="Copiar mensaje del evento para WhatsApp"
                                            data-titulo="{{ $evento->titulo }}"
                                            data-fecha="{{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('l, d \d\e F \d\e Y, H:i') }} hrs"
                                            data-lugar="{{ $evento->lugar ?? 'No especificado' }}"
                                            data-descripcion="{{ $evento->descripcion }}">
                                        <x-icons.share class="w-6 h-6" />
                                    </button>
                                    <a href="{{ route('eventos.edit', $evento) }}" title="Editar" class="text-gray-400 hover:text-accent-600 transition-colors duration-200">
                                        <x-icons.pencil class="w-6 h-6" />
                                    </a>
                                    <form action="{{ route('eventos.destroy', $evento) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Eliminar" class="text-gray-400 hover:text-danger-600 transition-colors duration-200">
                                            <x-icons.trash class="w-6 h-6" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-gray-500">No hay eventos registrados.</div>
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