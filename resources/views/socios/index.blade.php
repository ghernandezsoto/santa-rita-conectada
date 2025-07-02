<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Socios') }}
            </h2>
            <a href="{{ route('socios.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Agregar Nuevo Socio
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
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Nombre</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">RUT</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Contacto</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Estado</th>
                                    <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @if ($socios->isEmpty())
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-gray-500">
                                            No hay socios registrados aún.
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($socios as $socio)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3 px-4">{{ $socio->nombre }}</td>
                                            <td class="py-3 px-4">{{ $socio->rut }}</td>
                                            <td class="py-3 px-4">{{ $socio->email ?: $socio->telefono }}</td>
                                            <td class="py-3 px-4">
                                                <span class="py-1 px-3 rounded-full text-xs {{ $socio->estado === 'Activo' ? 'bg-green-200 text-green-700' : 'bg-red-200 text-red-700' }}">
                                                    {{ $socio->estado }}
                                                </span>
                                            </td>
                                            {{-- CELDA DE ACCIONES (LA PARTE MODIFICADA) --}}
                                            <td class="py-3 px-4 flex items-center gap-2">
                                                <a href="{{ route('socios.edit', $socio->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">Editar</a>
                                                
                                                <span class="text-gray-300">|</span>

                                                <form action="{{ route('socios.destroy', $socio->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium" onclick="return confirm('¿Estás seguro de que quieres eliminar a este socio?')">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>