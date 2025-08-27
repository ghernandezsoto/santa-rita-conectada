<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Socios') }}
            </h2>
            <a href="{{ route('socios.create') }}" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                Agregar Nuevo Socio
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Mensaje de éxito --}}
                    @if (session('success'))
                        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Formulario de Búsqueda --}}
                    <div class="mb-4">
                        <form action="{{ route('socios.index') }}" method="GET">
                            <div class="flex items-center">
                                <input type="text" name="search" placeholder="Buscar por nombre o RUT..." class="border-gray-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-md shadow-sm w-full sm:w-1/3" value="{{ request('search') }}">
                                <button type="submit" class="ml-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                                    Buscar
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        {{-- Usamos table-fixed para que respete los anchos definidos --}}
                        <table class="min-w-full bg-white table-fixed">
                            <thead class="bg-emerald-800 text-white">
                                <tr>
                                    {{-- CAMBIOS: Añadimos clases de ancho (w-x/12) a cada cabecera --}}
                                    <th class="w-1/12 py-3 px-4 font-semibold text-sm text-left">N° Socio</th>
                                    <th class="w-4/12 py-3 px-4 font-semibold text-sm text-left">Nombre</th>
                                    <th class="w-2/12 py-3 px-4 font-semibold text-sm text-left">RUT</th>
                                    <th class="w-2/12 py-3 px-4 font-semibold text-sm text-left">Contacto</th>
                                    <th class="w-1/12 py-3 px-4 font-semibold text-sm text-left">Estado</th>
                                    <th class="w-2/12 py-3 px-4 font-semibold text-sm text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @if ($socios->isEmpty())
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-gray-500">
                                            No hay socios que coincidan con la búsqueda o no hay socios registrados.
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($socios as $socio)
                                        <tr class="border-b hover:bg-slate-50">
                                            <td class="py-3 px-4 min-w-0">{{ $socio->id }}</td>

                                            {{-- Nombre: truncado con tooltip, responsive max-widths --}}
                                            <td class="py-3 px-4 min-w-0">
                                                <a href="{{ route('socios.show', $socio->id) }}"
                                                   title="{{ $socio->nombre }}"
                                                   class="inline-block font-medium text-emerald-800 hover:underline hover:text-emerald-900
                                                          max-w-[160px] sm:max-w-[240px] md:max-w-[320px] truncate whitespace-nowrap overflow-hidden">
                                                    {{ $socio->nombre }}
                                                </a>
                                            </td>

                                            {{-- RUT: truncado para evitar salto de línea --}}
                                            <td class="py-3 px-4 min-w-0">
                                                <span class="inline-block max-w-[100px] sm:max-w-[140px] md:max-w-[160px] truncate whitespace-nowrap overflow-hidden">
                                                    {{ $socio->rut }}
                                                </span>
                                            </td>

                                            {{-- Contacto: email o teléfono, truncado --}}
                                            <td class="py-3 px-4 min-w-0">
                                                <span title="{{ $socio->email ?: $socio->telefono }}" class="inline-block max-w-[140px] sm:max-w-[180px] md:max-w-[220px] truncate whitespace-nowrap overflow-hidden">
                                                    {{ $socio->email ?: $socio->telefono }}
                                                </span>
                                            </td>

                                            {{-- Estado --}}
                                            <td class="py-3 px-4 min-w-0">
                                                <span class="py-1 px-3 rounded-full text-xs font-semibold
                                                    {{ $socio->estado === 'Activo' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-200 text-red-700' }}">
                                                    {{ $socio->estado }}
                                                </span>
                                            </td>

                                            {{-- Acciones --}}
                                            <td class="py-3 px-4 min-w-0">
                                                <div class="flex items-center gap-2 whitespace-nowrap">
                                                    <a href="{{ route('socios.edit', $socio->id) }}" class="text-amber-600 hover:text-amber-700 font-medium">Editar</a>
                                                    <span class="text-gray-300">|</span>
                                                    <form action="{{ route('socios.destroy', $socio->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium" onclick="return confirm('¿Estás seguro de que quieres eliminar a este socio?')">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                        {{-- Enlaces de Paginación --}}
                        <div class="mt-4">
                            {{ $socios->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
