<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Eventos') }}
            </h2>
            <a href="{{ route('eventos.create') }}" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                Agregar Nuevo Evento
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Este es un div para mostrar una notificación flotante cuando se copia el texto --}}
                    <div id="copy-notification" class="hidden fixed top-20 right-5 bg-emerald-600 text-white py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300">
                        ¡Texto copiado al portapapeles!
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-emerald-800 text-white">
                                <tr>
                                    <th class="py-3 px-4 font-semibold text-sm text-left">Título del Evento</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left">Fecha y Hora</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left">Lugar</th>
                                    <th class="py-3 px-4 font-semibold text-sm text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($eventos as $evento)
                                    <tr class="border-b hover:bg-slate-50">
                                        <td class="py-3 px-4 font-medium text-emerald-800">{{ $evento->titulo }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d/m/Y H:i') }} hrs</td>
                                        <td class="py-3 px-4">{{ $evento->lugar ?? 'No especificado' }}</td>
                                        <td class="py-3 px-4 flex items-center gap-3">
                                            {{-- NUEVO BOTÓN PARA COPIAR --}}
                                            <button
                                                class="copy-btn text-blue-600 hover:text-blue-800"
                                                title="Copiar para WhatsApp"
                                                {{-- Estos atributos 'data' guardan la información del evento --}}
                                                data-titulo="{{ $evento->titulo }}"
                                                data-fecha="{{ \Carbon\Carbon::parse($evento->fecha_evento)->translatedFormat('l, d \d\e F \d\e Y, H:i') }} hrs"
                                                data-lugar="{{ $evento->lugar ?? 'No especificado' }}"
                                                data-descripcion="{{ $evento->descripcion }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                                                </svg>
                                            </button>

                                            <a href="{{ route('eventos.edit', $evento) }}" class="text-amber-600 hover:text-amber-700 font-medium">Editar</a>
                                            <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-700 font-medium" onclick="return confirm('¿Estás seguro?')">
                                                    Eliminar
                                                </button>
                                            </form>
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

                        <div class="mt-4">
                            {{ $eventos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT PARA LA FUNCIONALIDAD DE COPIAR --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const copyButtons = document.querySelectorAll('.copy-btn');
            const notification = document.getElementById('copy-notification');

            copyButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const titulo = this.dataset.titulo;
                    const fecha = this.dataset.fecha;
                    const lugar = this.dataset.lugar;
                    const descripcion = this.dataset.descripcion;

                    // Formateamos el texto para que se vea bien en WhatsApp (*...* es para negrita)
                    const whatsappText = `*¡Recordatorio de Evento!* 📢\n\n*Evento:* ${titulo}\n*Fecha:* ${fecha}\n*Lugar:* ${lugar}\n\n*Descripción:*\n${descripcion}\n\n¡No faltes!`;

                    // Usamos la API del navegador para copiar el texto
                    navigator.clipboard.writeText(whatsappText).then(() => {
                        // Mostramos la notificación
                        notification.classList.remove('hidden');
                        notification.classList.add('opacity-100');

                        // Ocultamos la notificación después de 2 segundos
                        setTimeout(() => {
                            notification.classList.remove('opacity-100');
                            notification.classList.add('hidden');
                        }, 2000);
                    }).catch(err => {
                        console.error('Error al copiar texto: ', err);
                    });
                });
            });
        });
    </script>
</x-app-layout>