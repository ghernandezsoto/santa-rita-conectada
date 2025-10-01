<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Próximos Eventos') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-base-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- VISTA DE ESCRITORIO (TABLA) --}}
                    <div class="hidden md:block">
                        <table class="min-w-full bg-white">
                            <thead class="bg-primary-800 text-white">
                                <tr>
                                    <th class="w-1/2 py-3 px-4 font-semibold text-sm text-left">Título del Evento</th>
                                    <th class="w-1/4 py-3 px-4 font-semibold text-sm text-left">Fecha y Hora</th>
                                    <th class="w-1/4 py-3 px-4 font-semibold text-sm text-left">Lugar</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($eventos as $evento)
                                    <tr class="border-b hover:bg-base-50">
                                        <td class="py-3 px-4">
                                            <a href="{{ route('portal.eventos.show', $evento) }}" class="font-medium text-primary-800 hover:underline truncate block" title="{{ $evento->titulo }}">
                                                {{ $evento->titulo }}
                                            </a>
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d/m/Y H:i') }} hrs</td>
                                        <td class="py-3 px-4 truncate" title="{{ $evento->lugar ?? 'No especificado' }}">{{ $evento->lugar ?? 'No especificado' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-6 text-center text-gray-500">
                                            No hay próximos eventos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- VISTA MÓVIL (TARJETAS) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($eventos as $evento)
                            <a href="{{ route('portal.eventos.show', $evento) }}" class="block bg-white p-4 rounded-lg shadow border border-gray-200 hover:bg-base-50">
                                <h3 class="font-bold text-primary-800 truncate" title="{{ $evento->titulo }}">{{ $evento->titulo }}</h3>
                                <p class="text-sm text-gray-600 mt-2">
                                    <span class="font-semibold">Fecha:</span> {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d/m/Y H:i') }} hrs
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold">Lugar:</span> {{ $evento->lugar ?? 'No especificado' }}
                                </p>
                            </a>
                        @empty
                            <div class="py-6 text-center text-gray-500">
                                No hay próximos eventos registrados.
                            </div>
                        @endforelse
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-6">
                        {{ $eventos->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>