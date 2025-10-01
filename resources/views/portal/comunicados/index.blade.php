<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Comunicados') }}
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
                                    <th class="w-1/2 py-3 px-4 font-semibold text-sm text-left">Título</th>
                                    <th class="w-1/4 py-3 px-4 font-semibold text-sm text-left">Publicado por</th>
                                    <th class="w-1/4 py-3 px-4 font-semibold text-sm text-left">Fecha de Envío</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($comunicados as $comunicado)
                                    <tr class="border-b hover:bg-base-50">
                                        <td class="py-3 px-4">
                                            {{-- El título es un enlace a la vista de detalle --}}
                                            <a href="{{ route('portal.comunicados.show', $comunicado) }}" class="font-medium text-primary-800 hover:underline truncate block" title="{{ $comunicado->titulo }}">
                                                {{ $comunicado->titulo }}
                                            </a>
                                        </td>
                                        <td class="py-3 px-4 truncate" title="{{ $comunicado->user->name }}">{{ $comunicado->user->name }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($comunicado->fecha_envio)->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-6 text-center text-gray-500">
                                            No hay comunicados para mostrar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- VISTA MÓVIL (TARJETAS) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($comunicados as $comunicado)
                            <a href="{{ route('portal.comunicados.show', $comunicado) }}" class="block bg-white p-4 rounded-lg shadow border border-gray-200 hover:bg-base-50">
                                <h3 class="font-bold text-primary-800 truncate" title="{{ $comunicado->titulo }}">{{ $comunicado->titulo }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Publicado por: {{ $comunicado->user->name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Fecha: {{ \Carbon\Carbon::parse($comunicado->fecha_envio)->format('d/m/Y') }}
                                </p>
                            </a>
                        @empty
                            <div class="py-6 text-center text-gray-500">
                                No hay comunicados para mostrar.
                            </div>
                        @endforelse
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-6">
                        {{ $comunicados->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>