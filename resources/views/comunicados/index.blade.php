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
                    <div id="copy-notification" class="hidden fixed top-20 right-5 bg-emerald-600 text-white py-2 px-4 rounded-lg shadow-lg">
                        ¡Texto copiado al portapapeles!
                    </div>

                    {{-- Vista de tabla (pantallas medianas y grandes) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full bg-white table-fixed">
                            <thead class="bg-emerald-800 text-white">
                                <tr>
                                    <th class="w-2/5 py-3 px-4 font-semibold text-sm text-left">Título</th>
                                    <th class="w-1/5 py-3 px-4 font-semibold text-sm text-left">Estado</th>
                                    <th class="w-1/5 py-3 px-4 font-semibold text-sm text-left">Enviado por</th>
                                    <th class="w-1/5 py-3 px-4 font-semibold text-sm text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($comunicados as $comunicado)
                                    <tr class="border-b hover:bg-slate-50">
                                        
                                        <td class="py-3 px-4">
                                            <div class="truncate max-w-md" title="{{ $comunicado->titulo }}">{{ $comunicado->titulo }}</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if ($comunicado->fecha_envio)
                                                <span class="bg-emerald-200 text-emerald-700 py-1 px-3 rounded-full text-xs">Enviado el {{ \Carbon\Carbon::parse($comunicado->fecha_envio)->format('d/m/Y') }}</span>
                                            @else
                                                <span class="bg-amber-100 text-amber-700 py-1 px-3 rounded-full text-xs">Borrador</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 truncate" title="{{ $comunicado->user->name }}">{{ $comunicado->user->name }}</td>
                                        <td class="py-3 px-4">
                                            {{-- Contenedor de acciones mejorado --}}
                                            <div class="flex items-center gap-2 flex-wrap">
                                                @if ($comunicado->fecha_envio)
                                                    <button type="button" class="copy-btn text-blue-600 hover:text-blue-800" title="Copiar para WhatsApp" data-titulo="{{ $comunicado->titulo }}" data-contenido="{{ $comunicado->contenido }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" /></svg>
                                                    </button>
                                                @endif
                                                <a href="{{ route('comunicados.show', $comunicado->id) }}" class="text-emerald-600 hover:text-emerald-900 font-medium">Ver</a>
                                                @if (!$comunicado->fecha_envio)
                                                    <a href="{{ route('comunicados.edit', $comunicado->id) }}" class="text-amber-600 hover:text-amber-700 font-medium">Editar</a>
                                                    <form action="{{ route('comunicados.enviar', $comunicado->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-amber-600 hover:text-amber-700 font-medium" onclick="return confirm('¿Enviar este comunicado a todos los socios activos?')">Enviar</button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('comunicados.destroy', $comunicado->id) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium" onclick="return confirm('¿Eliminar este comunicado?')">Eliminar</button>
                                                </form>
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
                            <div class="bg-white border rounded-lg shadow-sm p-4">
                                
                                <h3 class="font-semibold text-lg truncate max-w-xs" title="{{ $comunicado->titulo }}">{{ $comunicado->titulo }}</h3>
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
</x-app-layout>