<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Comunicados') }}
            </h2>
            <a href="{{ route('comunicados.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Nuevo Comunicado
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Mensaje de éxito --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Título</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Fecha de Creación</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Enviado por</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($comunicados as $comunicado)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">{{ $comunicado->titulo }}</td>
                                        <td class="py-3 px-4">{{ $comunicado->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="py-3 px-4">{{ $comunicado->user->name }}</td>
                                        
                                        {{-- CELDA DE ACCIONES COMPLETA Y CORREGIDA --}}
                                        <td class="py-3 px-4 flex items-center gap-2">
                                            <a href="{{ route('comunicados.show', $comunicado->id) }}" class="text-green-600 hover:text-green-900 font-medium">Ver</a>
                                            <span class="text-gray-300">|</span>
                                            
                                            {{-- Enlace para Editar añadido aquí --}}
                                            <a href="{{ route('comunicados.edit', $comunicado->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">Editar</a>
                                            <span class="text-gray-300">|</span>
                                            
                                            <form action="{{ route('comunicados.destroy', $comunicado->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium" onclick="return confirm('¿Estás seguro de que quieres eliminar este comunicado?')">
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