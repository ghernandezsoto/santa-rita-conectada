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

                    {{-- Tabla de comunicados --}}
                    <div class="overflow-x-auto">
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
                                        {{-- Título --}}
                                        <td class="py-3 px-4 max-w-0">
                                            <div class="truncate" title="{{ $comunicado->titulo }}">
                                                {{ $comunicado->titulo }}
                                            </div>
                                        </td>

                                        {{-- Estado --}}
                                        <td class="py-3 px-4">
                                            @if ($comunicado->fecha_envio)
                                                <span class="bg-emerald-200 text-emerald-700 py-1 px-3 rounded-full text-xs">
                                                    Enviado el {{ \Carbon\Carbon::parse($comunicado->fecha_envio)->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="bg-amber-100 text-amber-700 py-1 px-3 rounded-full text-xs">
                                                    Borrador
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Usuario --}}
                                        <td class="py-3 px-4">
                                            {{ $comunicado->user->name }}
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="py-3 px-4 flex items-center gap-2 whitespace-nowrap">
                                            <a href="{{ route('comunicados.show', $comunicado->id) }}" 
                                               class="text-emerald-600 hover:text-emerald-900 font-medium">
                                                Ver
                                            </a>
                                            <span class="text-gray-300">|</span>

                                            @if (!$comunicado->fecha_envio)
                                                <a href="{{ route('comunicados.edit', $comunicado->id) }}" 
                                                   class="text-amber-600 hover:text-amber-700 font-medium">
                                                    Editar
                                                </a>
                                                <span class="text-gray-300">|</span>

                                                <form action="{{ route('comunicados.enviar', $comunicado->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-amber-600 hover:text-amber-700 font-medium"
                                                            onclick="return confirm('¿Estás seguro de que quieres enviar este comunicado a todos los socios activos?')">
                                                        Enviar
                                                    </button>
                                                </form>
                                                <span class="text-gray-300">|</span>
                                            @endif

                                            <form action="{{ route('comunicados.destroy', $comunicado->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-700 font-medium"
                                                        onclick="return confirm('¿Estás seguro de que quieres eliminar este comunicado?')">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-gray-500">
                                            No hay comunicados registrados aún.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
